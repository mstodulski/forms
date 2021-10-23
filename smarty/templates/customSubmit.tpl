{strip}
    {assign var=formField value=$form->getField($fieldName)}
    {assign var=options value=$formField->getOptions()}
    {assign var=label value="Zatwierdź"}
    {if isset($options.label)}
        {assign var=label value=$options.label}
    {/if}

    <input type="submit" value="{$label} - CUSTOM" style="background-color: yellow;">
{/strip}