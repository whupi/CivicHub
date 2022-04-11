<?php

namespace PHPMaker2022\civichub2;

// Page object
$VotingSummary = &$Page;
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Voting: currentTable } });
var currentForm, currentPageID;
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<a id="top"></a>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
<!-- Content Container -->
<div id="ew-report" class="ew-report container-fluid">
<?php } ?>
<div class="btn-toolbar ew-toolbar">
<?php
if (!$Page->DrillDownInPanel) {
    $Page->ExportOptions->render("body");
    $Page->SearchOptions->render("body");
    $Page->FilterOptions->render("body");
}
?>
</div>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
<div class="row">
<?php } ?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
<!-- Center Container -->
<div id="ew-center" class="<?= $Page->CenterContentClass ?>">
<?php } ?>
<!-- Summary report (begin) -->
<div id="report_summary">
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<?php } ?>
<?php
while ($Page->GroupCount <= count($Page->GroupRecords) && $Page->GroupCount <= $Page->DisplayGroups) {
?>
<?php
    // Show header
    if ($Page->ShowHeader) {
?>
<?php if ($Page->GroupCount > 1) { ?>
</tbody>
</table>
</div>
<!-- /.ew-grid-middle-panel -->
<!-- Report grid (end) -->
<?php if (!$Page->isExport() && !($Page->DrillDown && $Page->TotalGroups > 0)) { ?>
<!-- Bottom pager -->
<div class="card-footer ew-grid-lower-panel">
<form name="ew-pager-form" class="ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
</form>
</div>
<?php } ?>
</div>
<!-- /.ew-grid -->
<?= $Page->PageBreakContent ?>
<?php } ?>
<div class="<?php if (!$Page->isExport("word") && !$Page->isExport("excel")) { ?>card ew-card <?php } ?>ew-grid"<?= $Page->ReportTableStyle ?>>
<!-- Report grid (begin) -->
<div id="gmp_Voting" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="<?= $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
    <tr class="ew-table-header">
<?php if ($Page->Submission_ID->Visible) { ?>
    <?php if ($Page->Submission_ID->ShowGroupHeaderAsRow) { ?>
    <th data-name="Submission_ID">&nbsp;</th>
    <?php } else { ?>
    <th data-name="Submission_ID" class="<?= $Page->Submission_ID->headerCellClass() ?>"><div class="Voting_Submission_ID"><?= $Page->renderFieldHeader($Page->Submission_ID) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->_Votes->Visible) { ?>
    <?php if ($Page->_Votes->ShowGroupHeaderAsRow) { ?>
    <th data-name="_Votes">&nbsp;</th>
    <?php } else { ?>
    <th data-name="_Votes" class="<?= $Page->_Votes->headerCellClass() ?>"><div class="Voting__Votes"><?= $Page->renderFieldHeader($Page->_Votes) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->_Title->Visible) { ?>
    <th data-name="_Title" class="<?= $Page->_Title->headerCellClass() ?>"><div class="Voting__Title"><?= $Page->renderFieldHeader($Page->_Title) ?></div></th>
<?php } ?>
<?php if ($Page->Count->Visible) { ?>
    <th data-name="Count" class="<?= $Page->Count->headerCellClass() ?>"><div class="Voting_Count"><?= $Page->renderFieldHeader($Page->Count) ?></div></th>
<?php } ?>
    </tr>
</thead>
<tbody>
<?php
        if ($Page->TotalGroups == 0) {
            break; // Show header only
        }
        $Page->ShowHeader = false;
    } // End show header
?>
<?php

    // Build detail SQL
    $where = DetailFilterSql($Page->Submission_ID, $Page->getSqlFirstGroupField(), $Page->Submission_ID->groupValue(), $Page->Dbid);
    if ($Page->PageFirstGroupFilter != "") {
        $Page->PageFirstGroupFilter .= " OR ";
    }
    $Page->PageFirstGroupFilter .= $where;
    if ($Page->Filter != "") {
        $where = "($Page->Filter) AND ($where)";
    }
    $sql = $Page->buildReportSql($Page->getSqlSelect(), $Page->getSqlFrom(), $Page->getSqlWhere(), $Page->getSqlGroupBy(), $Page->getSqlHaving(), $Page->getSqlOrderBy(), $where, $Page->Sort);
    $rs = $sql->execute();
    $Page->DetailRecords = $rs ? $rs->fetchAll() : [];
    $Page->DetailRecordCount = count($Page->DetailRecords);

    // Load detail records
    $Page->Submission_ID->Records = &$Page->DetailRecords;
    $Page->Submission_ID->LevelBreak = true; // Set field level break
        $Page->GroupCounter[1] = $Page->GroupCount;
        $Page->Submission_ID->getCnt($Page->Submission_ID->Records); // Get record count
?>
<?php if ($Page->Submission_ID->Visible && $Page->Submission_ID->ShowGroupHeaderAsRow) { ?>
<?php
        // Render header row
        $Page->resetAttributes();
        $Page->RowType = ROWTYPE_TOTAL;
        $Page->RowTotalType = ROWTOTAL_GROUP;
        $Page->RowTotalSubType = ROWTOTAL_HEADER;
        $Page->RowGroupLevel = 1;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->Submission_ID->Visible) { ?>
        <?php $Page->Submission_ID->CellAttrs->appendClass("ew-rpt-grp-caret"); ?>
        <td data-field="Submission_ID"<?= $Page->Submission_ID->cellAttributes(); ?>><i class="ew-group-toggle fas fa-caret-down"></i></td>
        <?php $Page->Submission_ID->CellAttrs->removeClass("ew-rpt-grp-caret"); ?>
<?php } ?>
        <td data-field="Submission_ID" colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount - 1) ?>"<?= $Page->Submission_ID->cellAttributes() ?>>
            <span class="ew-summary-caption Voting_Submission_ID"><?= $Page->renderFieldHeader($Page->Submission_ID) ?></span><?= $Language->phrase("SummaryColon") ?><span<?= $Page->Submission_ID->viewAttributes() ?>><?= $Page->Submission_ID->GroupViewValue ?></span>
            <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><?= FormatNumber($Page->Submission_ID->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span>
        </td>
    </tr>
<?php } ?>
<?php
    $Page->_Votes->getDistinctValues($Page->Submission_ID->Records);
    $Page->setGroupCount(count($Page->_Votes->DistinctValues), $Page->GroupCounter[1]);
    $Page->GroupCounter[2] = 0; // Init group count index
    foreach ($Page->_Votes->DistinctValues as $_Votes) { // Load records for this distinct value
        $Page->_Votes->setGroupValue($_Votes); // Set group value
        $Page->_Votes->getDistinctRecords($Page->Submission_ID->Records, $Page->_Votes->groupValue());
        $Page->_Votes->LevelBreak = true; // Set field level break
        $Page->GroupCounter[2]++;
        $Page->_Votes->getCnt($Page->_Votes->Records); // Get record count
        $Page->setGroupCount($Page->_Votes->Count, $Page->GroupCounter[1], $Page->GroupCounter[2]);
?>
<?php if ($Page->_Votes->Visible && $Page->_Votes->ShowGroupHeaderAsRow) { ?>
<?php
        // Render header row
        $Page->_Votes->setDbValue($_Votes); // Set current value for Votes
        $Page->resetAttributes();
        $Page->RowType = ROWTYPE_TOTAL;
        $Page->RowTotalType = ROWTOTAL_GROUP;
        $Page->RowTotalSubType = ROWTOTAL_HEADER;
        $Page->RowGroupLevel = 2;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->Submission_ID->Visible) { ?>
        <td data-field="Submission_ID"<?= $Page->Submission_ID->cellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->_Votes->Visible) { ?>
        <?php $Page->_Votes->CellAttrs->appendClass("ew-rpt-grp-caret"); ?>
        <td data-field="_Votes"<?= $Page->_Votes->cellAttributes(); ?>><i class="ew-group-toggle fas fa-caret-down"></i></td>
        <?php $Page->_Votes->CellAttrs->removeClass("ew-rpt-grp-caret"); ?>
<?php } ?>
        <td data-field="_Votes" colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount - 2) ?>"<?= $Page->_Votes->cellAttributes() ?>>
            <span class="ew-summary-caption Voting__Votes"><?= $Page->renderFieldHeader($Page->_Votes) ?></span><?= $Language->phrase("SummaryColon") ?><span<?= $Page->_Votes->viewAttributes() ?>><?= $Page->_Votes->GroupViewValue ?></span>
            <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><?= FormatNumber($Page->_Votes->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span>
        </td>
    </tr>
<?php } ?>
<?php
        $Page->RecordCount = 0; // Reset record count
        foreach ($Page->_Votes->Records as $record) {
            $Page->RecordCount++;
            $Page->RecordIndex++;
            $Page->loadRowValues($record);
?>
<?php
        // Render detail row
        $Page->resetAttributes();
        $Page->RowType = ROWTYPE_DETAIL;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->Submission_ID->Visible) { ?>
    <?php if ($Page->Submission_ID->ShowGroupHeaderAsRow) { ?>
        <td data-field="Submission_ID"<?= $Page->Submission_ID->cellAttributes() ?>></td>
    <?php } else { ?>
        <td data-field="Submission_ID"<?= $Page->Submission_ID->cellAttributes() ?>><span<?= $Page->Submission_ID->viewAttributes() ?>><?= $Page->Submission_ID->GroupViewValue ?></span></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->_Votes->Visible) { ?>
    <?php if ($Page->_Votes->ShowGroupHeaderAsRow) { ?>
        <td data-field="_Votes"<?= $Page->_Votes->cellAttributes() ?>></td>
    <?php } else { ?>
        <td data-field="_Votes"<?= $Page->_Votes->cellAttributes() ?>><span<?= $Page->_Votes->viewAttributes() ?>><?= $Page->_Votes->GroupViewValue ?></span></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->_Title->Visible) { ?>
        <td data-field="_Title"<?= $Page->_Title->cellAttributes() ?>>
<span<?= $Page->_Title->viewAttributes() ?>>
<?= $Page->_Title->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->Count->Visible) { ?>
        <td data-field="Count"<?= $Page->Count->cellAttributes() ?>>
<span<?= $Page->Count->viewAttributes() ?>>
<?= $Page->Count->getViewValue() ?></span>
</td>
<?php } ?>
    </tr>
<?php
    }
    } // End group level 1
?>
<?php

    // Next group
    $Page->loadGroupRowValues();

    // Show header if page break
    if ($Page->isExport()) {
        $Page->ShowHeader = ($Page->ExportPageBreakCount == 0) ? false : ($Page->GroupCount % $Page->ExportPageBreakCount == 0);
    }

    // Page_Breaking server event
    if ($Page->ShowHeader) {
        $Page->pageBreaking($Page->ShowHeader, $Page->PageBreakContent);
    }
    $Page->GroupCount++;
} // End while
?>
<?php if ($Page->TotalGroups > 0) { ?>
</tbody>
<tfoot>
</tfoot>
</table>
</div>
<!-- /.ew-grid-middle-panel -->
<!-- Report grid (end) -->
<?php if (!$Page->isExport() && !($Page->DrillDown && $Page->TotalGroups > 0)) { ?>
<!-- Bottom pager -->
<div class="card-footer ew-grid-lower-panel">
<form name="ew-pager-form" class="ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
</form>
</div>
<?php } ?>
</div>
<!-- /.ew-grid -->
<?php } ?>
</div>
<!-- /#report-summary -->
<!-- Summary report (end) -->
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
</div>
<!-- /#ew-center -->
<?php } ?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
</div>
<!-- /.row -->
<?php } ?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
<!-- Bottom Container -->
<div class="row">
    <div id="ew-bottom" class="<?= $Page->BottomContentClass ?>">
<?php } ?>
<?php
if (!$DashboardReport) {
    // Set up page break
    if (($Page->isExport("print") || $Page->isExport("pdf") || $Page->isExport("email") || $Page->isExport("excel") && Config("USE_PHPEXCEL") || $Page->isExport("word") && Config("USE_PHPWORD")) && $Page->ExportChartPageBreak) {
        // Page_Breaking server event
        $Page->pageBreaking($Page->ExportChartPageBreak, $Page->PageBreakContent);

        // Set up chart page break
        $Page->Votes->PageBreakType = "before"; // Page break type
        $Page->Votes->PageBreak = $Page->ExportChartPageBreak;
        $Page->Votes->PageBreakContent = $Page->PageBreakContent;
    }

    // Set up chart drilldown
    $Page->Votes->DrillDownInPanel = $Page->DrillDownInPanel;
    $Page->Votes->render("ew-chart-bottom");
}
?>
<?php if (!$DashboardReport && !$Page->isExport("email") && !$Page->DrillDown && $Page->Votes->hasData()) { ?>
<?php if (!$Page->isExport()) { ?>
<div class="mb-3"><a class="ew-top-link" data-ew-action="scroll-top"><?= $Language->phrase("Top") ?></a></div>
<?php } ?>
<?php } ?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
    </div>
</div>
<!-- /#ew-bottom -->
<?php } ?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
</div>
<!-- /.ew-report -->
<?php } ?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php if (!$Voting->isExport()) { ?>
<script>
loadjs.ready("jqueryjs", function() {
	var expires = new Date(new Date().getTime() + 525600 * 60 * 1000); // expire in 525600 
	var SearchToggle = $('.ew-search-toggle'); 
	var SearchPanel = $('.ew-search-panel'); 
	if (Cookies.get('Voting_searchpanel')=="active") { 
		SearchToggle.addClass(Cookies.get('Voting_searchpanel')); 
		SearchPanel.removeClass('collapse'); 
		SearchPanel.addClass('show'); 
		SearchToggle.addClass('active'); 
		SearchToggle.attr('aria-pressed', 'true'); 
	} else if (Cookies.get('Voting_searchpanel')=="notactive") { 
		SearchPanel.removeClass('show'); 
		SearchPanel.addClass('collapse'); 
		SearchToggle.removeClass('active'); 
		SearchToggle.attr('aria-pressed', 'false'); 
	} else { 
		SearchPanel.removeClass('show'); 	
		SearchPanel.addClass('collapse'); 
		SearchToggle.removeClass('active'); 
		SearchToggle.attr('aria-pressed', 'false'); 
	} 
	SearchToggle.on('click', function(event) { 
		event.preventDefault(); 
		if (SearchToggle.hasClass('active')) { 
			SearchToggle.removeClass('active'); 
			SearchToggle.attr('aria-pressed', 'true');
			Cookies.set("Voting_searchpanel", "notactive", { path: '', expires: expires }); 
		} else { 
			SearchToggle.addClass('active'); 		
			SearchToggle.attr('aria-pressed', 'false'); 
			Cookies.set("Voting_searchpanel", "active", { path: '', expires: expires }); 
		} 
	});
});
</script>
<?php } ?>
<?php } ?>
