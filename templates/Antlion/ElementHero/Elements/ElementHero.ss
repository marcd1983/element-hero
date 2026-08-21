<%-- <% require themedCSS('hero') %> --%>
<% require css('antlion/element-hero:client/css/hero.css') %>
<% cached $ID, $LastEdited, $Links.Count, $Links.Max('LastEdited') %>
<div class="hero-section hero-{$Theme} hero-h-{$Height}"<% if $HeroStyles %> style="{$HeroStyles}"<% end_if %>>
  <div class="hero-overlay"<% if $OverlayOpacity %> style="--hero-overlay:{$OverlayOpacityCss};"<% end_if %>></div>

  <div class="hero-inner grid-x  {$HorizontalAlignClass} {$VerticalAlignClass}">
    <div class="cell large-shrink small-auto {$PaddingClass}">
      <% if $Title && $ShowTitle %>
          <% with $HeadingTag %>
              <{$Me} class="hero-title">$Up.Title.XML</{$Me}>
          <% end_with %>
      <% end_if %>
  
      <% if $Content %>$Content<% end_if %>

      <% if $Links.Exists %>
        <div class="button-group {$HorizontalAlignClass}">
          <% loop $Links %>
            <a class="button $CssClass $ExtraClass" href="$URL" <% if $OpenInNew %>target="_blank" rel="noopener noreferrer"<% end_if %>>$Title.XML</a>
          <% end_loop %>
        </div>
      <% end_if %>
    </div>
  </div>
</div>
<% end_cached %>