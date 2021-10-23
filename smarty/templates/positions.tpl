{strip}
    <table>
        <thead>
        <tr>
            <th>
                Nazwa
            </th>
            <th>
                Ilość
            </th>
            <th>
                Data wysyłki
            </th>
            <th>
                Cena
            </th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$formField->getFields() item=subform}
            <tr>
                {foreach from=$subform->getFields() key=fieldName item=field}
                    <td>
                        {formControl form=$subform fieldName=$fieldName}
                        {formError form=$subform fieldName=$fieldName}
                    </td>
                {/foreach}
            </tr>
        {/foreach}
        </tbody>
    </table>
{/strip}