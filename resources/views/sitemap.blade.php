{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    @foreach($urls as $url)
        <url>
            @if(!empty($url['url']))
                <loc>{{ url($url['url']) }}</loc>
            @endif
            @if(!empty($url['lastModificationDate']))
                <lastmod>{{ $url['lastModificationDate'] }}</lastmod>
            @endif
            @if(!empty($url['changeFrequency']))
                <changefreq>{{ $url['changeFrequency'] }}</changefreq>
            @endif
            @if(!empty($url['priority']))
                <priority>{{ $url['priority'] }}</priority>
            @endif
        </url>
    @endforeach

</urlset>
