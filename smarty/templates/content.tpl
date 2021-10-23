{extends file="index.tpl"}
{block name="content"}
    {strip}
        {include file="formsFunctions.tpl"}
        {call form form=$form}

{*        {call formControl form=$form fieldName='warehouseDocuments'}<br/>*}
{*        {call formControl form=$form fieldName='categories'}<br/>*}
{*        {call formControl form=$form fieldName='dateTime'}<br/>*}

{*        {call formControl form=$form fieldName='positions'}<br/>*}
{*        {call formControl form=$form fieldName='customer'}<br/>*}
    {/strip}
{/block}