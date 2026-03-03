<!DOCTYPE html>
<html lang="{{ $currentLocale }}" dir="{{ $currentDir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locale Helpers Test - {{ locale_name() }}</title>
    <style>
        body {
            font-family: {{ locale_font() }};
            direction: {{ locale_dir() }};
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        .test-card {
            background: #f5f5f5;
            padding: 1.5rem;
            margin: 1rem 0;
            border-radius: 8px;
            border-left: 4px solid #4CAF50;
        }
        .rtl .test-card {
            border-left: none;
            border-right: 4px solid #4CAF50;
        }
        h1 {
            color: #333;
        }
        .code {
            background: #272822;
            color: #f8f8f2;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
        }
        .success {
            color: #4CAF50;
        }
    </style>
</head>
<body class="{{ is_rtl() ? 'rtl' : 'ltr' }}">
    
    <h1>{{ locale_flag() }} Locale Helpers Test {{ locale_flag() }}</h1>
    
    <div class="test-card">
        <h3>📍 Global Variables (View::share)</h3>
        <ul>
            <li><strong>$currentLocale:</strong> <code>{{ $currentLocale }}</code></li>
            <li><strong>$currentDir:</strong> <code>{{ $currentDir }}</code></li>
            <li><strong>$availableLocales:</strong> <code>{{ implode(', ', $availableLocales) }}</code></li>
        </ul>
    </div>

    <div class="test-card">
        <h3>🔧 Helper Functions</h3>
        <ul>
            <li><strong>locale_dir():</strong> <code>{{ locale_dir() }}</code></li>
            <li><strong>is_rtl():</strong> <code>{{ is_rtl() ? 'true ✅' : 'false ❌' }}</code></li>
            <li><strong>is_ltr():</strong> <code>{{ is_ltr() ? 'true ✅' : 'false ❌' }}</code></li>
            <li><strong>locale_font():</strong> <code>{{ locale_font() }}</code></li>
            <li><strong>locale_name():</strong> <code>{{ locale_name() }}</code></li>
            <li><strong>locale_flag():</strong> <code>{{ locale_flag() }}</code></li>
        </ul>
    </div>

    <div class="test-card">
        <h3>🎨 Blade Directives</h3>
        <ul>
            <li><strong>@locale:</strong> <code>@locale</code></li>
            <li><strong>@dir:</strong> <code>@dir</code></li>
            <li><strong>@flag:</strong> <code>@flag</code></li>
            <li><strong>@localeName:</strong> <code>@localeName</code></li>
        </ul>
    </div>

    <div class="test-card">
        <h3>🌍 Available Locales</h3>
        <ul>
            @foreach($localeNames as $code => $name)
                <li>
                    <strong>{{ $code }}:</strong> {{ $name }} 
                    @if($code === $currentLocale)
                        <span class="success">(Current ✓)</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    @isRtl
    <div class="test-card">
        <h3>✅ RTL Mode Active</h3>
        <p>This block only shows when @isRtl directive is true</p>
    </div>
    @endisRtl

    @isLtr
    <div class="test-card">
        <h3>✅ LTR Mode Active</h3>
        <p>This block only shows when @isLtr directive is true</p>
    </div>
    @endisLtr

    <div class="test-card">
        <h3>📝 Usage Examples</h3>
        
        <p><strong>In Blade Templates:</strong></p>
        <div class="code">
&lt;html lang="{{ $currentLocale }}" dir="{{ $currentDir }}"&gt;<br>
&lt;div style="font-family: {{ locale_font() }}"&gt;<br>
@if(is_rtl())<br>
&nbsp;&nbsp;&nbsp;&nbsp;/* RTL specific code */<br>
@endif
        </div>

        <p><strong>In PHP/Controllers:</strong></p>
        <div class="code">
$direction = locale_dir();<br>
if (is_rtl()) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;// Handle RTL<br>
}<br>
$font = locale_font($locale);
        </div>
    </div>

</body>
</html>
