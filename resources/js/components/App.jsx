import {
    About,
    Collab,
    Constellation,
    EscapeVelocity,
    Footer,
    Hero,
    Navbar,
    OrbitProjects,
    Transmission,
    YearProgress,
} from './';

export default function App() {
    const portfolioData = window.__PORTFOLIO_DATA__ ?? {};
    const focusProjectId = portfolioData.focusProjectId ?? null;

    return (
        <main>
            <Navbar />
            <Hero />
            <YearProgress />
            <About />
            <Transmission logs={portfolioData.logs} />
            <Constellation skills={portfolioData.skills} />
            <OrbitProjects projects={portfolioData.projects} focusProjectId={focusProjectId} />
            <EscapeVelocity stats={portfolioData.stats} />
            <Collab collab={portfolioData.collab} />
            <Footer />
        </main>
    );
}
