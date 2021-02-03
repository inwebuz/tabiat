{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @for($i = 1; $i <= $filesQuantity; $i++)
    <sitemap>
        <loc>{{ route('home') . '/sitemap' . $i . '.xml' }}</loc>
        <lastmod>{{ $sitemapLastmod }}</lastmod>
     </sitemap>
    @endfor
 </sitemapindex>
