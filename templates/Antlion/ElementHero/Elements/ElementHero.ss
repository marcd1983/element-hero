<%-- <% require themedCSS('hero') %> --%>
<% require css('antlion/element-hero:client/css/hero.css') %>
<div class="hero-section hero-{$Theme} hero-h-{$Height}" <% if $BackgroundImage %>style="background-image:url('{$BackgroundImage.FillMax(2000,1200).URL}');"<% end_if %>>
  <% if $OverlayOpacity %>
    <div class="hero-overlay" style="--hero-overlay: {$OverlayOpacityCss};"></div>
  <% else %>
    <div class="hero-overlay"></div>
  <% end_if %>

  <div class="hero-inner grid-x  {$HorizontalAlignClass} {$VerticalAlignClass}">
    <div class="cell large-shrink small-auto {$PaddingClass}">
      <% if $Title && $ShowTitle %>
          <% with $HeadingTag %>
              <{$Me} class="hero-title">$Up.Title.XML</{$Me}>
          <% end_with %>
      <% end_if %>
      <% if $Subtitle %><p class="subtitle">$Subtitle.XML</p><% end_if %>
      <% if $Content %><p class="blurb">$Content.XML</p><% end_if %>

      <% if $Links.Exists %>
        <div class="button-group {$HorizontalAlignClass}">
          <% loop $Links %>
            <a class="button $CssClass" href="$URL" <% if $OpenInNew %>target="_blank" rel="noopener noreferrer"<% end_if %>>$Title.XML</a>
          <% end_loop %>
        </div>
      <% end_if %>
    </div>
  </div>
</div>