{strip}
    {function formLabel form=null fieldName=null}
        {assign var=formField value=$form->getField($fieldName)}
        {assign var=options value=$formField->getOptions()}
        {if $formField->getType() != 'submitType' and $formField->getType() != 'hiddenType'}
            {if isset($options.label)}
                {$options.label}<br/>
            {/if}
        {/if}
    {/function}

    {function formError form=null fieldName=null}
        {assign var=formField value=$form->getField($fieldName)}
        {if $formField->getError() !== null}
            <small style="color: red; font-size: 8px;">{$formField->getError()->getErrorMessage()}</small><br/>
        {/if}
    {/function}

    {function formControl form=null fieldName=null}
        {assign var=formField value=$form->getField($fieldName)}
        {assign var=options value=$formField->getOptions()}
        {if $formField->getType() == 'textType'}
            {if $options.template === null}
                <input type="text" id="{$formField->getId()}" name="{$formField->getName()}" value="{$formField->getValue()}" class="">
            {else}
                {include file=$options.template formField=$formField}
            {/if}
        {elseif $formField->getType() == 'integerType'}
            {if $options.template === null}
                <input type="text" id="{$formField->getId()}" name="{$formField->getName()}" value="{$formField->getValue()}" class="integer-type">
            {else}
                {include file=$options.template formField=$formField}
            {/if}
        {elseif $formField->getType() == 'floatType'}
            {if $options.template === null}
                <input type="text" id="{$formField->getId()}" name="{$formField->getName()}" value="{$formField->getValue()}" class="float-type">
            {else}
                {include file=$options.template formField=$formField}
            {/if}
        {elseif $formField->getType() == 'dateTimeType'}
            {if $options.template === null}
                <input type="text" id="{$formField->getId()}" name="{$formField->getName()}" value="{$formField->getValue()}" class="datetime">
            {else}
                {include file=$options.template formField=$formField}
            {/if}
        {elseif $formField->getType() == 'textareaType'}
            {if $options.template === null}
                <textarea id="{$formField->getId()}" name="{$formField->getName()}" class="">{$formField->getValue()}</textarea>
            {else}
                {include file=$options.template formField=$formField}
            {/if}
        {elseif $formField->getType() == 'hiddenType'}
            <input type="hidden" id="{$formField->getId()}" name="{$formField->getName()}" value="{$formField->getValue()}" class="">
        {elseif $formField->getType() == 'passwordType'}
            {if $options.template === null}
                <input type="password" id="{$formField->getId()}" name="{$formField->getName()}" class="">
            {else}
                {include file=$options.template formField=$formField}
            {/if}
        {elseif $formField->getType() == 'selectType'}
            {if $options.template === null}
                {assign var=options value=$formField->getOptions()}
                {assign var=choices value=$options.choices}
                <select id="{$formField->getId()}" name="{$formField->getName()}">
                    {foreach from=$choices key=id item=label}
                        <option value="{$id}" {if $id == $formField->getValue()}selected{/if} >{$label}</option>
                    {/foreach}
                </select>
            {else}
                {include file=$options.template formField=$formField}
            {/if}
        {elseif $formField->getType() == 'multiSelectType'}
            {if $options.template === null}
                {assign var=options value=$formField->getOptions()}
                {assign var=choices value=$options.choices}
                <select id="{$formField->getId()}" name="{$formField->getName()}[]" multiple="multiple">
                    {foreach from=$choices key=id item=label}
                        <option value="{$id}" {if $id|in_array:$formField->getValue()}selected{/if} >{$label}</option>
                    {/foreach}
                </select>
            {else}
                {include file=$options.template formField=$formField}
            {/if}
        {elseif $formField->getType() == 'checkboxType'}
            {if $options.template === null}
                {assign var=choices value=$options.choices}

                {foreach from=$choices key=id item=label name=checkboxes}
                    <label for="{$formField->getId()}_{$id}">
                        <input type="checkbox" id="{$formField->getId()}_{$id}" name="{$formField->getName()}[]" value="{$id}" {if $id|in_array:$formField->getValue()}checked{/if} >&nbsp;{$label}
                        {if not $smarty.foreach.checkboxes.last}<br/>{/if}
                    </label>
                {/foreach}
            {else}
                {include file=$options.template formField=$formField}
            {/if}
        {elseif $formField->getType() == 'radiobuttonType'}
            {if $options.template === null}
                {assign var=choices value=$options.choices}

                {foreach from=$choices key=id item=label name=radiobuttons}
                    <label for="{$formField->getId()}_{$id}">
                        <input type="radio" id="{$formField->getId()}_{$id}_{$smarty.foreach.radiobuttons.index}" name="{$formField->getName()}" value="{$id}" {if $id == $formField->getValue()}checked{/if} >&nbsp;{$label}
                        {if not $smarty.foreach.radiobuttons.last}<br/>{/if}
                    </label>
                {/foreach}
            {else}
                {include file=$options.template formField=$formField}
            {/if}
        {elseif $formField->getType() == 'form'}
            {foreach from=$formField->getFields() key=fieldName item=field}
                {formLabel form=$formField fieldName=$fieldName}
                {formControl form=$formField fieldName=$fieldName}
                {formError form=$formField fieldName=$fieldName}
            {/foreach}
        {elseif $formField->getType() == 'collection'}
            {if $options.template === null}
                {foreach from=$formField->getFields() item=subform}
                    {foreach from=$subform->getFields() key=fieldName item=field}
                        {formLabel form=$subform fieldName=$fieldName}
                        {formControl form=$subform fieldName=$fieldName}
                        {formError form=$subform fieldName=$fieldName}
                    {/foreach}

                {/foreach}
            {else}
                {include file=$options.template formField=$formField}
            {/if}
        {elseif $formField->getType() == 'submitType'}
            {if $options.template === null}
                {assign var=formField value=$form->getField($fieldName)}
                {assign var=options value=$formField->getOptions()}
                {assign var=label value="Zatwierdź"}
                {if isset($options.label)}
                    {assign var=label value=$options.label}
                {/if}

                <input type="submit" value="{$label}">
            {else}
                {include file=$options.template formField=$formField}
            {/if}
        {elseif $formField->getType() == 'fileType'}
            <input type="file" id="{$formField->getId()}" name="{$formField->getName()}[]"  class="" multiple>
        {/if}
        <br/>
    {/function}

    {function formWidget form=null fieldName=null}
        {formLabel form=$form fieldName=$fieldName}
        {formControl form=$form fieldName=$fieldName}
        {formError form=$form fieldName=$fieldName}
    {/function}

    {function form form=null fieldName=null}
        <form method="post" enctype="multipart/form-data">
            {foreach from=$form->getFields() key=fieldName item=formField}
                {formWidget form=$form fieldName=$fieldName}
            {/foreach}
        </form>
    {/function}
{/strip}