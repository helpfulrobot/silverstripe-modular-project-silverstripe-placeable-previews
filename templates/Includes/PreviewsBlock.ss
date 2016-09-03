<div {$ClassAttr}{$AnchorAttr}>
    <% loop Previews %>
        <a href="{$LinkURL}" class="preview {$Classes}"{$TargetAttr}>
            <% if PreviewImage %>
                <% with PreviewImage %>
                    <img src="$Fill(320,200).Link" alt="{$Title}" aria-hidden="true" />
                <% end_with %>
            <% end_if %>
            <% if Title %>
                <span class="title">
                    {$Title}
                </span>
            <% end_if %>
            <% if PreviewSummary %>
                <div class="summary">
                    <p>
                        {$PreviewSummary}
                    </p>
                </div>
            <% end_if %>
            <% if PreviewMore %>
                <span class="more">
                    {$PreviewMore}
                </span>
            <% end_if %>
        </a>
    <% end_loop %>
</div>
