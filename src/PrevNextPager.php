<?php

namespace PHPMaker2022\civichub2;

/**
 * PrevNext pager class
 */
class PrevNextPager extends Pager
{
    public $PageCount;
    public $CurrentPageNumber;
    public $Modal;

    // Constructor
    public function __construct($tableVar, $fromIndex, $pageSize, $recordCount, $pageSizes = "", $range = 10, $autoHidePager = null, $autoHidePageSizeSelector = null, $usePageSizeSelector = null, $isModal = false)
    {
        parent::__construct($tableVar, $fromIndex, $pageSize, $recordCount, $pageSizes, $range, $autoHidePager, $autoHidePageSizeSelector, $usePageSizeSelector);
        $this->FirstButton = new PagerItem();
        $this->PrevButton = new PagerItem();
        $this->NextButton = new PagerItem();
        $this->LastButton = new PagerItem();
        $this->Modal = $isModal;
        $this->init();
    }

    // Init pager
    protected function init()
    {
        $this->CurrentPageNumber = (int)(($this->FromIndex - 1) / $this->PageSize) + 1;
        if ($this->CurrentPageNumber <= 0) { // Make sure page number >= 1
            $this->CurrentPageNumber = 1;
        }
        $this->PageCount = (int)(($this->RecordCount - 1) / $this->PageSize) + 1;
        if ($this->AutoHidePager && $this->PageCount == 1) {
            $this->Visible = false;
        }
        if ($this->FromIndex > $this->RecordCount) {
            $this->FromIndex = $this->RecordCount;
        }
        $this->ToIndex = $this->FromIndex + $this->PageSize - 1;
        if ($this->ToIndex > $this->RecordCount) {
            $this->ToIndex = $this->RecordCount;
        }

        // First Button
        $tempIndex = 1;
        $this->FirstButton->Start = $tempIndex;
        $this->FirstButton->Enabled = ($tempIndex != $this->FromIndex);

        // Prev Button
        $tempIndex = $this->FromIndex - $this->PageSize;
        if ($tempIndex < 1) {
            $tempIndex = 1;
        }
        $this->PrevButton->Start = $tempIndex;
        $this->PrevButton->Enabled = ($tempIndex != $this->FromIndex);

        // Next Button
        $tempIndex = $this->FromIndex + $this->PageSize;
        if ($tempIndex > $this->RecordCount) {
            $tempIndex = $this->FromIndex;
        }
        $this->NextButton->Start = $tempIndex;
        $this->NextButton->Enabled = ($tempIndex != $this->FromIndex);

        // Last Button
        $tempIndex = (int)(($this->RecordCount - 1) / $this->PageSize) * $this->PageSize + 1;
        $this->LastButton->Start = $tempIndex;
        $this->LastButton->Enabled = ($tempIndex != $this->FromIndex);
    }

    // Render
    public function render()
    {
        global $Language;
        $html = "";
        if ($this->isVisible()) {
            if ($this->FirstButton->Enabled) {
                $firstBtn = '<a class="btn btn-default" data-table="' . $this->TableVar . '" title="' . $Language->phrase("PagerFirst") . '"' . $this->getStartAttribute($this->FirstButton->Start) . ' data-ew-action="redirect"><i class="icon-first ew-icon"></i></a>';
            } else {
                $firstBtn = '<a class="btn btn-default disabled" title="' . $Language->phrase("PagerFirst") . '" data-ew-action="redirect"><i class="icon-first ew-icon"></i></a>';
            }
            if ($this->PrevButton->Enabled) {
                $prevBtn = '<a class="btn btn-default" data-table="' . $this->TableVar . '" title="' . $Language->phrase("PagerPrevious") . '"' . $this->getStartAttribute($this->PrevButton->Start) . ' data-ew-action="redirect"><i class="icon-prev ew-icon"></i></a>';
            } else {
                $prevBtn = '<a class="btn btn-default disabled" title="' . $Language->phrase("PagerPrevious") . '" data-ew-action="redirect"><i class="icon-prev ew-icon"></i></a>';
            }
            if ($this->NextButton->Enabled) {
                $nextBtn = '<a class="btn btn-default" data-table="' . $this->TableVar . '" title="' . $Language->phrase("PagerNext") . '"' . $this->getStartAttribute($this->NextButton->Start) . ' data-ew-action="redirect"><i class="icon-next ew-icon"></i></a>';
            } else {
                $nextBtn = '<a class="btn btn-default disabled" title="' . $Language->phrase("PagerNext") . '" data-ew-action="redirect"><i class="icon-next ew-icon"></i></a>';
            }
            if ($this->LastButton->Enabled) {
                $lastBtn = '<a class="btn btn-default" data-table="' . $this->TableVar . '" title="' . $Language->phrase("PagerLast") . '"' . $this->getStartAttribute($this->LastButton->Start) . ' data-ew-action="redirect"><i class="icon-last ew-icon"></i></a>';
            } else {
                $lastBtn = '<a class="btn btn-default disabled" title="' . $Language->phrase("PagerLast") . '" data-ew-action="redirect"><i class="icon-last ew-icon"></i></a>';
            }
            $formatInteger = self::$FormatIntegerFunc;
            $pagePhrase = $Language->phrase($this->PagePhraseId);
            $pageNumber = '<!-- current page number --><input class="form-control ew-page-no" type="text" data-pagesize="' . $this->PageSize . '" data-pagecount="' . $this->PageCount . '" name="' . $this->PageNumberName . '" value="' . $formatInteger($this->CurrentPageNumber) . '"' . ($this->Modal ? " disabled" : "") . '>';
            $html = <<<PAGER
                <div class="ew-pager">
                    <span>{$pagePhrase}&nbsp;</span>
                    <div class="ew-prev-next">
                        <div class="input-group input-group-sm">
                            <!-- first page button -->
                            {$firstBtn}
                            <!-- previous page button -->
                            {$prevBtn}
                            {$pageNumber}
                            <!-- next page button -->
                            {$nextBtn}
                            <!-- last page button -->
                            {$lastBtn}
                        </div>
                    </div>
                    <span>&nbsp;{$Language->phrase("Of")}&nbsp;{$formatInteger($this->PageCount)}</span>
                </div>
                PAGER;
            $html .= parent::render();
        }
        return $html;
    }

    // Get start attribute
    protected function getStartAttribute($start)
    {
        if ($this->Modal) {
            return ' data-start="' . $start . '"';
        } else {
            return ' href="' . CurrentPageUrl(false) . '?start=' . $start . '"';
        }
    }
}
