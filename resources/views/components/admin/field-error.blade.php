@props(['name'])

@error($name)
    <p class="text-xs text-warn" role="alert">{{ $message }}</p>
@enderror
