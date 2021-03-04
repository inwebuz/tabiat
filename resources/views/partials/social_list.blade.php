@php
    $telegram = setting('contact.telegram');
    $facebook = setting('contact.facebook');
    $instagram = setting('contact.instagram');
@endphp
<ul>
    @if ($telegram)
        <li><a class="social-btn telegram" href="{{ setting('contact.telegram') }}" title="Telegram" target="_blank" rel="nofollow"><i class="fab fa-telegram-plane"></i></a></li>
    @endif
    @if ($facebook)
        <li><a class="social-btn facebook" href="{{ setting('contact.facebook') }}" title="Facebook" target="_blank" rel="nofollow"><i class="fab fa-facebook-f"></i></a></li>
    @endif
    @if ($instagram)
        <li><a class="social-btn instagram" href="{{ setting('contact.instagram') }}" title="Instagram" target="_blank" rel="nofollow"><i class="fab fa-instagram"></i></a></li>
    @endif
</ul>
