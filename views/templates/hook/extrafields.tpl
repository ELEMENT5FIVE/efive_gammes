<div class="m-b-1 m-t-1">
    <h2>{l s='Range & Specificities' mod='efivegammespec'}</h2>

    <fieldset class="form-group">
        <div class="col-lg-12 col-xl-4">
            <label class="form-control-label">{l s='Range field' mod='efivegammespec'}</label>
            <div class="translations tabbable">
                <div class="translationsFields tab-content">
                    <div
                        class="tab-pane translation-label-{$default_language} active">
                        <select name="id_gamme" class="form-control">
                            <option value="0">{l s='Select a range' mod='efivegammespec'}</option>
                            {foreach from=$listGamme item=gamme}
                                <option value="{$gamme.id_gamme}" {if $gamme.id_gamme == $id_gamme}selected="true" {/if}>
                                    {$gamme.name}
                                </option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-4">
            <label class="form-control-label">{l s='Specification field' mod='efivegammespec'}</label>
            <div class="translations tabbable">
                <div class="translationsFields tab-content">
                    <div
                        class="tab-pane translation-label-{$default_language} active">
                        <select name="id_specification" class="form-control">
                            <option value="0">{l s='Select a range' mod='efivegammespec'}</option>
                            {foreach from=$listSpecification item=specification}
                                <option value="{$specification.id_specification}" {if $specification.id_specification == $id_specification}selected="true" {/if}>
                                    {$specification.name}
                                </option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <div class="clearfix"></div>
</div>