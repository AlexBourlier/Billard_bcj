<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport\Concerns;

trait InteractsWithTelematHtmlFixtures
{
    protected function validTelematHtml(): string
    {
        return <<<'HTML'
<html>
<body>
    <table id="licenses">
    <thead>
        <tr>
        <th>Num&eacute;ro</th>
        <th>Nom</th>
        <th>Pr&eacute;nom</th>
        <th>Cat&eacute;gorie</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <td><a href="./?cs=token-1">191100 S</a></td>
        <td><a href="./?cs=token-1">ARROUAS</a></td>
        <td><a href="./?cs=token-1">ISABELLE</a></td>
        <td class="c"><a href="./?cs=token-1">Decouverte</a></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
        <tr>
        <td><a href="./?cs=token-2">190399 F</a></td>
        <td><a href="./?cs=token-2">AUCHART</a></td>
        <td><a href="./?cs=token-2">THIERRY</a></td>
        <td class="c"><a href="./?cs=token-2">Decouverte</a></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
    </tbody>
    </table>
</body>
</html>
HTML;
    }

    protected function telematHtmlWithSingleValidRow(): string
    {
        return <<<'HTML'
<html>
<body>
    <table id="licenses">
    <thead>
        <tr>
        <th>Num&eacute;ro</th>
        <th>Nom</th>
        <th>Pr&eacute;nom</th>
        <th>Cat&eacute;gorie</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <td><a href="./?cs=token-1">191100 S</a></td>
        <td><a href="./?cs=token-1">ARROUAS</a></td>
        <td><a href="./?cs=token-1">ISABELLE</a></td>
        <td class="c"><a href="./?cs=token-1">Decouverte</a></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
    </tbody>
    </table>
</body>
</html>
HTML;
    }

    protected function telematHtmlWithMissingRequiredFirstNameOnSecondRow(): string
    {
        return <<<'HTML'
<html>
<body>
    <table id="licenses">
    <thead>
        <tr>
        <th>Num&eacute;ro</th>
        <th>Nom</th>
        <th>Pr&eacute;nom</th>
        <th>Cat&eacute;gorie</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <td><a href="./?cs=token-1">191100 S</a></td>
        <td><a href="./?cs=token-1">ARROUAS</a></td>
        <td><a href="./?cs=token-1">ISABELLE</a></td>
        <td class="c"><a href="./?cs=token-1">Decouverte</a></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
        <tr>
        <td><a href="./?cs=token-2">190399 F</a></td>
        <td><a href="./?cs=token-2">AUCHART</a></td>
        <td>&nbsp;</td>
        <td class="c"><a href="./?cs=token-2">Decouverte</a></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
    </tbody>
    </table>
</body>
</html>
HTML;
    }

    protected function telematHtmlWithMultipleTablesWhereFallbackFindsTheRightOne(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="layout-table">
            <thead>
                <tr>
                    <th>Information</th>
                    <th>Valeur</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Version</td>
                    <td>1</td>
                </tr>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Num&eacute;ro</th>
                    <th>Nom</th>
                    <th>Pr&eacute;nom</th>
                    <th>Cat&eacute;gorie</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href="./?cs=token-1">191100 S</a></td>
                    <td><a href="./?cs=token-1">ARROUAS</a></td>
                    <td><a href="./?cs=token-1">ISABELLE</a></td>
                    <td class="c"><a href="./?cs=token-1">Decouverte</a></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><a href="./?cs=token-2">190399 F</a></td>
                    <td><a href="./?cs=token-2">AUCHART</a></td>
                    <td><a href="./?cs=token-2">THIERRY</a></td>
                    <td class="c"><a href="./?cs=token-2">Decouverte</a></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWithMultipleCandidateTablesWhereFallbackMustSelectBestMatch(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table>
            <thead>
                <tr>
                    <th>Information</th>
                    <th>Valeur</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Version</td>
                    <td>1</td>
                </tr>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Num&eacute;ro</th>
                    <th>Nom</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>PARTIAL-001</td>
                    <td>TABLE-PARTIELLE</td>
                </tr>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Num&eacute;ro</th>
                    <th>Nom</th>
                    <th>Pr&eacute;nom</th>
                    <th>Cat&eacute;gorie</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href="./?cs=token-1">191100 S</a></td>
                    <td><a href="./?cs=token-1">ARROUAS</a></td>
                    <td><a href="./?cs=token-1">ISABELLE</a></td>
                    <td class="c"><a href="./?cs=token-1">Decouverte</a></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><a href="./?cs=token-2">190399 F</a></td>
                    <td><a href="./?cs=token-2">AUCHART</a></td>
                    <td><a href="./?cs=token-2">THIERRY</a></td>
                    <td class="c"><a href="./?cs=token-2">Decouverte</a></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWithMultipleEquallyMatchingTablesWhereFallbackMustKeepFirstOne(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table>
            <thead>
                <tr>
                    <th>Num&eacute;ro</th>
                    <th>Nom</th>
                    <th>Pr&eacute;nom</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>FIRST-001</td>
                    <td>PREMIERE</td>
                    <td>TABLE</td>
                </tr>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Num&eacute;ro</th>
                    <th>Nom</th>
                    <th>Pr&eacute;nom</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>SECOND-001</td>
                    <td>SECONDE</td>
                    <td>TABLE</td>
                </tr>
            </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWithRepeatedHeaderRowInsideBody(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Num&eacute;ro</th>
            <th>Nom</th>
            <th>Pr&eacute;nom</th>
            <th>Cat&eacute;gorie</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>Num&eacute;ro</td>
            <td>Nom</td>
            <td>Pr&eacute;nom</td>
            <td>Cat&eacute;gorie</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td><a href="./?cs=token-1">191100 S</a></td>
            <td><a href="./?cs=token-1">ARROUAS</a></td>
            <td><a href="./?cs=token-1">ISABELLE</a></td>
            <td class="c"><a href="./?cs=token-1">Decouverte</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td><a href="./?cs=token-2">190399 F</a></td>
            <td><a href="./?cs=token-2">AUCHART</a></td>
            <td><a href="./?cs=token-2">THIERRY</a></td>
            <td class="c"><a href="./?cs=token-2">Decouverte</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWithEmptyRowInsideBody(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Num&eacute;ro</th>
            <th>Nom</th>
            <th>Pr&eacute;nom</th>
            <th>Cat&eacute;gorie</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><a href="./?cs=token-1">191100 S</a></td>
            <td><a href="./?cs=token-1">ARROUAS</a></td>
            <td><a href="./?cs=token-1">ISABELLE</a></td>
            <td class="c"><a href="./?cs=token-1">Decouverte</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td><a href="./?cs=token-2">190399 F</a></td>
            <td><a href="./?cs=token-2">AUCHART</a></td>
            <td><a href="./?cs=token-2">THIERRY</a></td>
            <td class="c"><a href="./?cs=token-2">Decouverte</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWithShorterSecondRow(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Num&eacute;ro</th>
            <th>Nom</th>
            <th>Pr&eacute;nom</th>
            <th>Cat&eacute;gorie</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><a href="./?cs=token-1">191100 S</a></td>
            <td><a href="./?cs=token-1">ARROUAS</a></td>
            <td><a href="./?cs=token-1">ISABELLE</a></td>
            <td><a href="./?cs=token-1">Decouverte</a></td>
            </tr>
            <tr>
            <td><a href="./?cs=token-2">190399 F</a></td>
            <td><a href="./?cs=token-2">AUCHART</a></td>
            <td><a href="./?cs=token-2">THIERRY</a></td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWithLongerSecondRow(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Num&eacute;ro</th>
            <th>Nom</th>
            <th>Pr&eacute;nom</th>
            <th>Cat&eacute;gorie</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><a href="./?cs=token-1">191100 S</a></td>
            <td><a href="./?cs=token-1">ARROUAS</a></td>
            <td><a href="./?cs=token-1">ISABELLE</a></td>
            <td><a href="./?cs=token-1">Decouverte</a></td>
            </tr>
            <tr>
            <td><a href="./?cs=token-2">190399 F</a></td>
            <td><a href="./?cs=token-2">AUCHART</a></td>
            <td><a href="./?cs=token-2">THIERRY</a></td>
            <td><a href="./?cs=token-2">Decouverte</a></td>
            <td><a href="./?cs=token-2">EXTRA-CELL</a></td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWithBodyRowContainingOnlyHeaderCells(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Num&eacute;ro</th>
            <th>Nom</th>
            <th>Pr&eacute;nom</th>
            <th>Cat&eacute;gorie</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <th colspan="6">Section licences actives</th>
            </tr>
            <tr>
            <td><a href="./?cs=token-1">191100 S</a></td>
            <td><a href="./?cs=token-1">ARROUAS</a></td>
            <td><a href="./?cs=token-1">ISABELLE</a></td>
            <td class="c"><a href="./?cs=token-1">Decouverte</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td><a href="./?cs=token-2">190399 F</a></td>
            <td><a href="./?cs=token-2">AUCHART</a></td>
            <td><a href="./?cs=token-2">THIERRY</a></td>
            <td class="c"><a href="./?cs=token-2">Decouverte</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWithoutTheadUsingFirstRowAsHeaders(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
            <tr>
                <th>Num&eacute;ro</th>
                <th>Nom</th>
                <th>Pr&eacute;nom</th>
                <th>Cat&eacute;gorie</th>
            </tr>
            <tr>
                <td><a href="./?cs=token-1">191100 S</a></td>
                <td><a href="./?cs=token-1">ARROUAS</a></td>
                <td><a href="./?cs=token-1">ISABELLE</a></td>
                <td><a href="./?cs=token-1">Decouverte</a></td>
            </tr>
            <tr>
                <td><a href="./?cs=token-2">190399 F</a></td>
                <td><a href="./?cs=token-2">AUCHART</a></td>
                <td><a href="./?cs=token-2">THIERRY</a></td>
                <td><a href="./?cs=token-2">Decouverte</a></td>
            </tr>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWithoutTheadUsingFirstTdRowAsHeaders(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
            <tr>
                <td>Num&eacute;ro</td>
                <td>Nom</td>
                <td>Pr&eacute;nom</td>
                <td>Cat&eacute;gorie</td>
            </tr>
            <tr>
                <td><a href="./?cs=token-1">191100 S</a></td>
                <td><a href="./?cs=token-1">ARROUAS</a></td>
                <td><a href="./?cs=token-1">ISABELLE</a></td>
                <td><a href="./?cs=token-1">Decouverte</a></td>
            </tr>
            <tr>
                <td><a href="./?cs=token-2">190399 F</a></td>
                <td><a href="./?cs=token-2">AUCHART</a></td>
                <td><a href="./?cs=token-2">THIERRY</a></td>
                <td><a href="./?cs=token-2">Decouverte</a></td>
            </tr>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWithNoisyButNormalizableHeaders(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th> Num&eacute;ro : </th>
            <th>Nom</th>
            <th>Pr&eacute;nom ?</th>
            <th>Cat&eacute;gorie /</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><a href="./?cs=token-1">191100 S</a></td>
            <td><a href="./?cs=token-1">ARROUAS</a></td>
            <td><a href="./?cs=token-1">ISABELLE</a></td>
            <td><a href="./?cs=token-1">Decouverte</a></td>
            </tr>
            <tr>
            <td><a href="./?cs=token-2">190399 F</a></td>
            <td><a href="./?cs=token-2">AUCHART</a></td>
            <td><a href="./?cs=token-2">THIERRY</a></td>
            <td><a href="./?cs=token-2">Decouverte</a></td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWherePrimaryAliasIsEmptyAndSecondaryAliasContainsValue(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Num&eacute;ro principal</th>
            <th>Num&eacute;ro</th>
            <th>Nom</th>
            <th>Pr&eacute;nom</th>
            <th>Cat&eacute;gorie</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>&nbsp;</td>
            <td>191100 S</td>
            <td>ARROUAS</td>
            <td>ISABELLE</td>
            <td>Decouverte</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>190399 F</td>
            <td>AUCHART</td>
            <td>THIERRY</td>
            <td>Decouverte</td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWhereMultipleAliasesMatchButFirstNonEmptyValueMustWin(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Num&eacute;ro principal</th>
            <th>Num&eacute;ro</th>
            <th>Nom</th>
            <th>Pr&eacute;nom</th>
            <th>Cat&eacute;gorie</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>PRIMARY-191100</td>
            <td>SECONDARY-191100</td>
            <td>ARROUAS</td>
            <td>ISABELLE</td>
            <td>Decouverte</td>
            </tr>
            <tr>
            <td>PRIMARY-190399</td>
            <td>SECONDARY-190399</td>
            <td>AUCHART</td>
            <td>THIERRY</td>
            <td>Decouverte</td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWhereMultipleHeadersNormalizeToSameKeyAndFirstValueMustWin(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Num&eacute;ro</th>
            <th>Num&eacute;ro :</th>
            <th>Nom</th>
            <th>Pr&eacute;nom</th>
            <th>Cat&eacute;gorie</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>PRIMARY-191100</td>
            <td>SECONDARY-191100</td>
            <td>ARROUAS</td>
            <td>ISABELLE</td>
            <td>Decouverte</td>
            </tr>
            <tr>
            <td>PRIMARY-190399</td>
            <td>SECONDARY-190399</td>
            <td>AUCHART</td>
            <td>THIERRY</td>
            <td>Decouverte</td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWhereMultipleHeadersNormalizeToSameKeyAndSecondValueReplacesEmptyFirstOne(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Num&eacute;ro</th>
            <th>Num&eacute;ro :</th>
            <th>Nom</th>
            <th>Pr&eacute;nom</th>
            <th>Cat&eacute;gorie</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>&nbsp;</td>
            <td>SECONDARY-191100</td>
            <td>ARROUAS</td>
            <td>ISABELLE</td>
            <td>Decouverte</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td>SECONDARY-190399</td>
            <td>AUCHART</td>
            <td>THIERRY</td>
            <td>Decouverte</td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematRealClubHtmlSnapshot(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <h1>Licences et pass billard scolaire</h1>

        <h2>Licences du club 15061 – BILLARD CLUB DE JOUE LES TOURS</h2>

        <table id="licenses">
            <thead>
                <tr>
                    <th>Num&eacute;ro</th>
                    <th>Nom</th>
                    <th>Pr&eacute;nom</th>
                    <th>Cat&eacute;gorie</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href="./?cs=token-1">191100 S</a></td>
                    <td><a href="./?cs=token-1">ARROUAS</a></td>
                    <td><a href="./?cs=token-1">ISABELLE</a></td>
                    <td><a href="./?cs=token-1">Decouverte</a></td>
                </tr>
                <tr>
                    <td><a href="./?cs=token-2">190399 F</a></td>
                    <td><a href="./?cs=token-2">AUCHART</a></td>
                    <td><a href="./?cs=token-2">THIERRY</a></td>
                    <td><a href="./?cs=token-2">Decouverte</a></td>
                </tr>
                <tr>
                    <td><a href="./?cs=token-3">018952 Y</a></td>
                    <td><a href="./?cs=token-3">AUGER</a></td>
                    <td><a href="./?cs=token-3">WILLIAM</a></td>
                    <td><a href="./?cs=token-3">+21 ans</a></td>
                </tr>
                <tr>
                    <td><a href="./?cs=token-4">185211 R</a></td>
                    <td><a href="./?cs=token-4">BAILLON</a></td>
                    <td><a href="./?cs=token-4">DIDIER</a></td>
                    <td><a href="./?cs=token-4">+21 ans</a></td>
                </tr>
                <tr>
                    <td><a href="./?cs=token-5">137219 R</a></td>
                    <td><a href="./?cs=token-5">BOUTEILLE</a></td>
                    <td><a href="./?cs=token-5">MATHEO</a></td>
                    <td><a href="./?cs=token-5">-21 ans</a></td>
                </tr>
            </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematRealisticHtmlWithoutConfiguredSelectorButWithFallbackCandidate(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <h1>Licences et pass billard scolaire</h1>

        <table class="layout-table">
            <thead>
                <tr>
                    <th>Information</th>
                    <th>Valeur</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Club</td>
                    <td>15061</td>
                </tr>
            </tbody>
        </table>

        <h2>Licences du club 15061 – BILLARD CLUB DE JOUE LES TOURS</h2>

        <table class="result-table">
            <thead>
                <tr>
                    <th>Num&eacute;ro</th>
                    <th>Nom</th>
                    <th>Pr&eacute;nom</th>
                    <th>Cat&eacute;gorie</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href="./?cs=token-1">191100 S</a></td>
                    <td><a href="./?cs=token-1">ARROUAS</a></td>
                    <td><a href="./?cs=token-1">ISABELLE</a></td>
                    <td><a href="./?cs=token-1">Decouverte</a></td>
                </tr>
                <tr>
                    <td><a href="./?cs=token-2">190399 F</a></td>
                    <td><a href="./?cs=token-2">AUCHART</a></td>
                    <td><a href="./?cs=token-2">THIERRY</a></td>
                    <td><a href="./?cs=token-2">Decouverte</a></td>
                </tr>
            </tbody>
        </table>
    </body>
    </html>
    HTML;
    }

    protected function telematHtmlWithHeadersThatDoNotMatchRequiredFieldMapping(): string
    {
        return <<<'HTML'
    <html>
    <body>
        <table id="licenses">
        <thead>
            <tr>
            <th>Licence</th>
            <th>Nom complet</th>
            <th>Pr&eacute;nom complet</th>
            <th>Classe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>191100 S</td>
            <td>ARROUAS</td>
            <td>ISABELLE</td>
            <td>Decouverte</td>
            </tr>
            <tr>
            <td>190399 F</td>
            <td>AUCHART</td>
            <td>THIERRY</td>
            <td>Decouverte</td>
            </tr>
        </tbody>
        </table>
    </body>
    </html>
    HTML;
    }
}