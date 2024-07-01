@props('wait')
    
    
    @if ($message ?? false)
    
    <div>
        <h1>{{ $message }}</h1>
    </div>
    
    @endif
