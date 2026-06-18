import { useEffect, useState } from 'react';

const API_URL = '/api/holidays';
const CACHE_KEY = 'year-progress-holidays-v2';
const CACHE_TTL = 86_400_000;

export function useHoliday() {
    const [holiday, setHoliday] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        let cancelled = false;
        const cached = getCache();
        const today = getTodayInJakarta();

        if (cached) {
            const match = cached.find((item) => item.holiday_date === today);
            setHoliday(match || null);
            setLoading(false);
            return () => { cancelled = true; };
        }

        fetch(API_URL)
            .then((res) => {
                if (!res.ok) throw new Error(`Holiday API failed: ${res.status}`);
                return res.json();
            })
            .then((json) => {
                if (cancelled) return;
                if (json.status !== 'success' || !Array.isArray(json.data)) return;
                saveCache(json.data);
                const match = json.data.find((item) => item.holiday_date === today);
                setHoliday(match || null);
            })
            .catch(() => {
                if (!cancelled) setHoliday(null);
            })
            .finally(() => {
                if (!cancelled) setLoading(false);
            });

        return () => { cancelled = true; };
    }, []);

    return { holiday, loading };
}

function getTodayInJakarta() {
    const parts = new Intl.DateTimeFormat('en-CA', {
        timeZone: 'Asia/Jakarta',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    }).formatToParts(new Date());

    const year = parts.find((part) => part.type === 'year')?.value;
    const month = parts.find((part) => part.type === 'month')?.value;
    const day = parts.find((part) => part.type === 'day')?.value;

    return `${year}-${month}-${day}`;
}

function getCache() {
    try {
        const raw = localStorage.getItem(CACHE_KEY);
        if (!raw) return null;
        const { data, timestamp } = JSON.parse(raw);
        if (Date.now() - timestamp > CACHE_TTL) {
            localStorage.removeItem(CACHE_KEY);
            return null;
        }
        return data;
    } catch {
        return null;
    }
}

function saveCache(data) {
    try {
        localStorage.setItem(CACHE_KEY, JSON.stringify({ data, timestamp: Date.now() }));
    } catch {}
}
