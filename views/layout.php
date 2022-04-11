<?php

namespace PHPMaker2022\civichub2;

// Base path
$basePath = BasePath(true);
?>
<!DOCTYPE html>
<html<?= IsRTL() ? ' lang="' . CurrentLanguageID() . '" dir="rtl"' : '' ?>>
<head>
<title>
<?php 
	// === Begin of modification Page Title variable, by Masino Sinaga, September 11, 2020 == \\
	global $sCurrentPageTitle, $CurrentPageTitle, $sLogoFilename;
	$sCurrentPageTitle = "";
	$sCurrentPageTitle = (getCurrentPageTitle(CurrentPageName()) != "") ? getCurrentPageTitle(CurrentPageName()) :  "";
	if (empty($sCurrentPageTitle)) {
		echo $Language->ProjectPhrase("BodyTitle");
	} else {
		echo $sCurrentPageTitle . " &laquo; " . $Language->ProjectPhrase("BodyTitle");
	}
	// === End of modification Page Title variable, by Masino Sinaga, September 11, 2020 == \\
?>
</title>
<meta charset="utf-8">
<?php if ($ReportExportType != "" && $ReportExportType != "print") { // Stylesheet for exporting reports ?>
<link rel="stylesheet" href="<?= $basePath ?><?= AutoVersion(CssFile(Config("PROJECT_STYLESHEET_FILENAME"))); ?>">
    <?php if ($ReportExportType == "pdf" && Config("PDF_STYLESHEET_FILENAME")) { ?>
<link rel="stylesheet" href="/<?= $basePath ?><?= AutoVersion(CssFile(Config("PROJECT_STYLESHEET_FILENAME"))); ?>"><!-- Absolute path with leading '/' -->
    <?php } ?>
<?php } ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?= $basePath ?>css/select2.min.css">
<link rel="stylesheet" href="<?= $basePath ?>css/select2-bootstrap5.min.css">
<link rel="stylesheet" href="<?= $basePath ?>adminlte3mod/css/<?= CssFile("adminlte.css") ?>?v=1640687422">
<link rel="stylesheet" href="<?= $basePath ?>css/sweetalert2.min.css">
<link rel="stylesheet" href="<?= $basePath ?>plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="<?= $basePath ?>css/OverlayScrollbars.min.css">
<link rel="stylesheet" href="<?= $basePath ?><?= AutoVersion(CssFile(Config("PROJECT_STYLESHEET_FILENAME"))); ?>">
<?php // === Begin modification of Alertify Notification, by Masino Sinaga, September 11, 2020  === \\ ?>
<?php if($Language->phrase("dir")=="rtl") { ?>
<link rel="stylesheet" href="<?= $basePath ?>plugins/alertifyjs/css/alertify.rtl.min.css?v=1640687422" id="alertify_style">
<link rel="stylesheet" href="<?= $basePath ?>plugins/alertifyjs/css/themes/bootstrap-flat.rtl.min.css?v=1640687422" id="alertify_theme">
<?php } else { ?>
<link rel="stylesheet" href="<?= $basePath ?>plugins/alertifyjs/css/alertify.min.css?v=1640687422" id="alertify_style">
<link rel="stylesheet" href="<?= $basePath ?>plugins/alertifyjs/css/themes/bootstrap-flat.min.css?v=1640687422" id="alertify_theme">
<?php } ?>
<?php // === End modification of Alertify Notification, by Masino Sinaga, September 11, 2020 === \\ ?>
<?php if ($CustomExportType == "pdf" && Config("PDF_STYLESHEET_FILENAME")) { ?>
<link rel="stylesheet" href="<?= $basePath ?><?= AutoVersion(CssFile(Config("PROJECT_STYLESHEET_FILENAME"))); ?>">
<?php } ?>
<link rel="stylesheet" href="<?= $basePath ?><?= AutoVersion("adminlte3mod/css/custom-style.css"); ?>">
<link rel="stylesheet" href="<?= $basePath ?>adminlte3mod/css/font-opensans.css?v=1640687422">
<?php // === Begin modification by Masino Sinaga, September 11, 2020 === \\ ?>
<?php if (Language()->Type == "DROPDOWN" || Language()->Type == "LI") { ?>
<link rel="stylesheet" href="<?= $basePath ?>plugins/language-selector-dropdown/flags.css?v=1640687422">
<?php } elseif (Language()->Type == "SELECT") { ?>
<?php if($Language->phrase("dir")=="rtl") { ?>
<link rel="stylesheet" href="<?= $basePath ?>plugins/language-selector-combobox/css/msdropdown/dd-rtl.css?v=1640687422">
<link rel="stylesheet" href="<?= $basePath ?>plugins/language-selector-dropdown/flags.css?v=1640687422">
<?php } else { ?>
<link rel="stylesheet" href="<?= $basePath ?>plugins/language-selector-combobox/css/msdropdown/dd.css?v=1640687422">
<link rel="stylesheet" href="<?= $basePath ?>plugins/language-selector-dropdown/flags.css?v=1640687422">
<?php } ?>
<link rel="stylesheet" href="<?= $basePath ?>plugins/language-selector-combobox/css/msdropdown/flags.css?v=1640687422">
<?php } ?>
<?php // === End modification by Masino Sinaga, September 11, 2020 ==- \\ ?>
<link rel="stylesheet" href="<?= $basePath ?>css/custom-v3.css">
<script data-pace-options='<?= JsonEncode(Config("PACE_OPTIONS")) ?>' src="<?= $basePath ?>js/pace.js"></script><!-- Single quotes for data-pace-options -->
<script src="<?= $basePath ?>js/element-internals-polyfill.min.js"></script>
<script src="<?= $basePath ?><?= AutoVersion("js/masinoewcore.js"); ?>"></script>
<script>
var document_title = document.title;
var $rowindex$ = null;
Object.assign(ew, <?= JsonEncode(ConfigClientVars()) ?>, <?= JsonEncode(GlobalClientVars()) ?>);
loadjs(ew.PATH_BASE + "jquery/jquery-3.6.0.min.js", "jquery");
loadjs(ew.PATH_BASE + "js/popper.min.js", "popper");
loadjs(ew.PATH_BASE + "js/luxon.min.js", "luxon");
loadjs([
    ew.PATH_BASE + "js/mobile-detect.min.js",
    ew.PATH_BASE + "js/purify.min.js",
    ew.PATH_BASE + "jquery/load-image.all.min.js"
], "others");
loadjs(ew.PATH_BASE + "js/sweetalert2.min.js", "swal");
<?= $Language->toJson() ?>
ew.vars = <?= JsonEncode(GetClientVar()) ?>;
ew.ready(["wrapper", "jquery"], ew.PATH_BASE + "jquery/jsrender.min.js", "jsrender", ew.renderJsTemplates);
ew.ready("jsrender", ew.PATH_BASE + "jquery/jquery.overlayScrollbars.min.js", "scrollbars"); // Init sidebar scrollbars after rendering menu
ew.ready("jquery", ew.PATH_BASE + "jquery/jquery-ui.min.js", "widget");
<?php // === Begin modification of Language Selector and Alertify Javascript, by Masino Sinaga, September 11, 2020  === \\ ?>
ew.ready("jquery", ew.PATH_BASE + "plugins/language-selector-combobox/js/msdropdown/jquery.dd.js?v=1640687422", "msdropdown");
ew.ready("jquery", ew.PATH_BASE + "plugins/alertifyjs/alertify.min.js?v=1640687422", "alertifyjs");
<?php // === End modification of Language Selector and Alertify Javascript, by Masino Sinaga, September 11, 2020  === \\ ?>
</script>
<?php include_once $RELATIVE_PATH . "views/menu.php"; ?>
<script>
var cssfiles = [
    ew.PATH_BASE + "css/jquery.fileupload.css",
    ew.PATH_BASE + "css/jquery.fileupload-ui.css"
];
cssfiles.push(ew.PATH_BASE + "colorbox/colorbox.css");
loadjs(cssfiles, "css");
var cssjs = [];
<?php foreach (array_merge(Config("STYLESHEET_FILES"), Config("JAVASCRIPT_FILES")) as $file) { // External Stylesheets and JavaScripts ?>
cssjs.push("<?= (IsRemote($file) ? "" : BasePath(true)) . $file ?>");
<?php } ?>
var jqueryjs = [
    ew.PATH_BASE + "jquery/select2.full.min.js",
    ew.PATH_BASE + "jquery/jqueryfileupload.min.js",
    ew.PATH_BASE + "jquery/typeahead.jquery.min.js",
	ew.PATH_BASE + "plugins/js-cookie/src/js.cookie.js?v=1640687422"
];
jqueryjs.push(ew.PATH_BASE + "colorbox/jquery.colorbox.min.js");
jqueryjs.push(ew.PATH_BASE + "js/pdfobject.min.js");
ew.ready(["jquery", "dom", "popper"], ew.PATH_BASE + "bootstrap5/js/bootstrap.min.js", "bootstrap"); // Bootstrap
ew.ready("bootstrap", ew.PATH_BASE + "adminlte3mod/js/adminlte.js?v=1640687422", "adminlte"); // AdminLTE (After Bootstrap)
ew.ready(["jquery", "widget", "msdropdown"], [jqueryjs], "jqueryjs");
ew.ready(["bootstrap", "adminlte", "jqueryjs", "scrollbars", "luxon", "others"], ew.PATH_BASE + "<?= AutoVersion("js/masinoew.js"); ?>", "makerjs");
ew.ready("makerjs", [
    cssjs,
    ew.PATH_BASE + "js/userfn.js",
    ew.PATH_BASE + "js/userevent.js"
], "head");
</script>
<script>
loadjs(ew.PATH_BASE + "css/<?= CssFile("tempus-dominus.css", false) ?>");
ew.ready("head", [
    ew.PATH_BASE + "js/tempus-dominus.min.js",
    ew.PATH_BASE + "js/ewdatetimepicker.min.js"
], "datetimepicker");
</script>
<script>
ew.ready("head", [ew.PATH_BASE + "ckeditor/ckeditor.js", ew.PATH_BASE + "js/eweditor.js"], "editor");
</script>
<script>
// Load chart *.js
loadjs(ew.PATH_BASE + "js/chart.min.js", "chartjs");
ew.ready(["chartjs", "luxon"], [
    ew.PATH_BASE + "js/chartjs-adapter-luxon.min.js",
    ew.PATH_BASE + "js/chartjs-plugin-annotation.min.js",
    ew.PATH_BASE + "js/chartjs-plugin-datalabels.min.js"
], "chart");
// Create chart
ew.createChart = function (args) {
    loadjs.ready(["head", "chart"], function () {
        let $ = jQuery,
            canvas = document.getElementById(args.canvasId),
            config = args.chartJson,
            showPercentage = args.showPercentage,
            yFieldFormat = args.yFieldFormat,
            yAxisFormat = args.yAxisFormat;
        canvas.dir = "ltr"; // Keep it LTR so currency symbol position in the format pattern will not be changed
        let formatNumber = function (value, format) {
            if (format == "Currency")
                return ew.formatCurrency(value, ew.CURRENCY_FORMAT);
            else if (format == "Number")
                return ew.formatNumber(value, ew.NUMBER_FORMAT);
            else if (format == "Percent")
                return ew.formatPercent(value, ew.PERCENT_FORMAT);
            return value;
        };
        if (config.data && config.data.datasets.length > 0) {
            config.options.onHover = function (e) {
                let el = this.getElementsAtEventForMode(e.native, "nearest", { intersect: true }, false);
                e.native.target.style.cursor = (el.length) ? "pointer" : "default";
            };
            let axis = config.options.indexAxis == "y" ? "x" : "y";
            if (!["pie", "doughnut"].includes(config.type)) { // Format x/y axis for non pie/doughnut charts
                // Format Primary Axis (x/y)
                config.options.scales[axis] = $.extend(true, {}, config.options.scales[axis], {
                    ticks: {
                        callback: function (value, index, values) {
                            let format = yAxisFormat.length ? yAxisFormat[0] : "";
                            return formatNumber(value, format);
                        }
                    }
                });
                // Format Secondary Axis (y1)
                if (config.options.scales["y1"]) {
                    config = $.extend(true, {}, config, {
                        options: {
                            scales: {
                                y1: {
                                    ticks: {
                                        callback: function (value, index, values) {
                                            let format = yAxisFormat.length > 1 ? yAxisFormat[1] : "";
                                            return formatNumber(value, format);
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }
            config = $.extend(true, {}, config, {
                plugins: [ChartDataLabels],
                options: {
                    plugins: {
                        datalabels: {
                            align: ["line", "area"].includes(config.type) ? "top" : ew.IS_RTL ? "right" : "center",
                            rotation: config.type == "bar" && config.options.indexAxis != "y" ? -90 : 0, // Rotate label -90 degrees for column chart
                            formatter: function (value, context) {
                                let format = yFieldFormat.length > context.datasetIndex ? yFieldFormat[context.datasetIndex] : (yFieldFormat.length > 0 ? yFieldFormat[0] : "");
                                if (["pie", "doughnut"].includes(config.type) && showPercentage) { // Show as percentage
                                    let sum = context.dataset.data.reduce((accum, val) => {
                                        return accum + val;
                                    });
                                    value = value / sum;
                                    format = "Percent";
                                }
                                return formatNumber(value, format);
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = ["pie", "doughnut"].includes(config.type) ? context.label || "" : "",
                                        value = context.raw,
                                        format = yFieldFormat.length > context.datasetIndex ? yFieldFormat[context.datasetIndex] : (yFieldFormat.length > 0 ? yFieldFormat[0] : "");
                                    if (label)
                                        label += ": ";
                                    if (["pie", "doughnut"].includes(config.type) && showPercentage) {
                                        let sum = context.dataset.data.reduce((accum, val) => {
                                            return accum + val;
                                        });
                                        value = value / sum;
                                        format = "Percent";
                                    }
                                    label += formatNumber(value, format);
                                    return label;
                                }
                            }
                        }
                    }
                }
            }, ew.chartConfig, ew.charts[args.id]); // Deep copy (chart config + global config + user chart config)
            let evtArgs = { id: args.id, ctx: canvas, config: config };
            $(document).trigger("chart", [evtArgs]);
            let chart = new Chart(evtArgs.ctx, evtArgs.config);
            if (ew.DEBUG)
                console.log(evtArgs.config);
            evtArgs.ctx.addEventListener("click", function (e) {
                let activePoints = chart.getElementsAtEventForMode(e, "index", { intersect: true }, false);
                if (activePoints[0]) {
                    let activePoint = activePoints[0],
                        links = chart.data.datasets[activePoint.datasetIndex].links,
                        link = Array.isArray(links) ? links[activePoint.index] : {};
                    if (args.useDrilldownPanel) {
                        ew.showDrillDown(null, canvas, link.url, link.id, link.hdr);
                    } else if (args.useDrilldownPanel === false) { // If null, no drilldown
                        return ew.redirect(link.url);
                    }
                }
            });
            window.exportCharts["chart_" + args.id] = chart; // Export chart
        } else {
            canvas.classList.add("d-none");
        }
    });
};
</script>
<?php // === Begin modification of Alertifyjs by Masino Sinaga, July 27, 2017 === \\ ?>
<script>
loadjs.ready("alertifyjs", function () {
	alertify.defaults.pinnable = true;alertify.defaults.modal = false;alertify.defaults.transition = "zoom";alertify.defaults.theme.ok = "btn btn-primary";alertify.defaults.theme.cancel = "btn btn-danger";alertify.defaults.theme.input = "form-control";
});
</script>
<?php // === End modification of Alertifyjs by Masino Sinaga, July 27, 2017 === \\ ?>
<!-- Navbar -->
<script type="text/html" id="navbar-menu-items" class="ew-js-template" data-name="navbar" data-seq="10" data-data="navbar" data-method="appendTo" data-target="#ew-navbar">
{{if items}}
    {{for items}}
        <li id="{{:id}}" data-name="{{:name}}" class="{{if parentId == -1}}nav-item ew-navbar-item{{/if}}{{if isHeader && parentId > -1}}dropdown-header{{/if}}{{if items}} dropdown{{/if}}{{if items && parentId != -1}} dropdown-submenu{{/if}}{{if items && level == 1}} dropdown-hover{{/if}} d-none d-md-block">
            {{if isHeader && parentId > -1}}
                {{if icon}}<i class="{{:icon}}"></i>{{/if}}
                <span>{{:text}}</span>
            {{else}}
            <a href="{{:href}}"{{if target}} target="{{:target}}"{{/if}} class="{{if parentId == -1}}nav-link{{else}}dropdown-item{{/if}}{{if active}} active{{/if}}{{if items}} dropdown-toggle ew-dropdown {{if open}} active {{/if}}{{/if}}"{{if items}} role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"{{/if}}{{if attrs}}{{:attrs}}{{/if}} data-ew-action="redirect">
                {{if icon}}<i class="{{:icon}}"></i>{{/if}}
                <span>{{:text}}</span>
            </a>
            {{/if}}
            {{if items}}
            <ul class="dropdown-menu">
                {{include tmpl="#navbar-menu-items"/}}
            </ul>
            {{/if}}
        </li>
    {{/for}}
{{/if}}
</script>
<!-- Sidebar -->
<script type="text/html" class="ew-js-template" data-name="menu" data-seq="10" data-data="menu" data-target="#ew-menu">
{{if items}}
    <ul class="nav nav-sidebar nav-child-indent nav-legacy flex-column{{if compact}} nav-compact{{/if}}" data-widget="treeview" role="menu" data-accordion="{{:accordion}}">
    {{include tmpl="#menu-items"/}}
    </ul>
{{/if}}
</script>
<script type="text/html" id="menu-items">
{{if items}}
    {{for items}}
        <li id="{{:id}}" data-name="{{:name}}" class="{{if isHeader}}nav-header{{else}}nav-item{{if items}} has-treeview{{/if}}{{if active}} active current{{/if}}{{if open}} menu-open{{/if}}{{/if}}{{if isNavbarItem}} d-block d-md-none{{/if}}">
            {{if isHeader}}
                {{if icon}}<i class="{{:icon}}"></i>{{/if}}
                <span>{{:text}}</span>
                {{if label}}
                <span class="right">
                    {{:label}}
                </span>
                {{/if}}
            {{else}}
            {{if href == "javascript:void(0);"}}
			<a href="{{:href}}" class="nav-link{{if (active || open)}} active{{/if}}"{{if target}} target="{{:target}}"{{/if}}{{if attrs}}{{:attrs}}{{/if}}>
			{{else}}
            <a href="{{:href}}" class="nav-link{{if (active || open)}} active{{/if}}"{{if target}} target="{{:target}}"{{/if}}{{if attrs}}{{:attrs}}{{/if}} data-ew-action="redirect">
			{{/if}}
                {{if icon}}<i class="nav-icon {{:icon}}"></i>{{/if}}
                <p>{{:text}}
                    {{if items}}
                        <i class="right fas fa-angle-left"></i>
                        {{if label}}
                            <span class="right">
                                {{:label}}
                            </span>
                        {{/if}}
                    {{else}}
                        {{if label}}
                            <span class="right">
                                {{:label}}
                            </span>
                        {{/if}}
                    {{/if}}
                </p>
            </a>
            {{/if}}
            {{if items}}
            <ul class="nav nav-treeview"{{if open}} style="display: block;"{{/if}}>
                {{include tmpl="#menu-items"/}}
            </ul>
            {{/if}}
        </li>
    {{/for}}
{{/if}}
</script>
<script type="text/html" class="ew-js-template" data-name="languages" data-seq="10" data-data="languages" data-method="<?= $Language->Method ?>" data-target="<?= HtmlEncode($Language->Target) ?>">
<?= $Language->getTemplate() ?>
</script>
<script type="text/html" class="ew-js-template" data-name="login" data-seq="10" data-data="login" data-method="appendTo" data-target=".navbar-nav.ms-auto">
{{if canSubscribe}}
<li class="nav-item"><a id="subscribe-notification" class="nav-link disabled">{{:subscribeText}}</a></li>
{{/if}}
{{if isLoggedIn}}
<li class="nav-item dropdown text-body">
    <a id="ew-nav-link-user" class="nav-link ew-user" data-bs-toggle="dropdown" href="#">
		<?php if (CurrentUserImageBase64()) { ?>
			<div class="user-panel d-flex">
                <div class="user-image">
                    <?= Config("MS_USER_CARD_USER_NAME"); ?>&nbsp;<img src="data:image/png;base64,<?= CurrentUserImageBase64() ?>" class="img-circle ew-user-image" alt="">
                </div>
			</div>
        <?php } else { ?>
			<div class="user-image">
		    <?= Config("MS_USER_CARD_USER_NAME"); ?>&nbsp;<i class="fas fa-user"></i>
			</div>
		<?php } ?>
    </a>
    <div class="dropdown-menu dropdown-menu-anim dropdown-menu-end" aria-labelledby="ew-nav-link-user">
		<div class="ms-user-card" style="background-image: url(<?= $basePath ?>assets/media/misc/head_bg_sm.jpg); top: -8px;">
			<div class="ms-user-card-wrapper">
				<div class="ms-user-card-pic">
					<img src="data:image/png;base64,<?= CurrentUserImageBase64() ?>" class="img-circle ew-user-image" alt="">
				</div>
				<div class="ms-user-card-details">
					<div class="ms-user-card-name"><?php echo Config("MS_USER_CARD_COMPLETE_NAME"); ?></div>
					<div class="ms-user-card-position"><?php echo Config("MS_USER_CARD_POSITION"); ?></div>
				</div>
			</div>
		</div>
		<a class="dropdown-item" id="dropdown-user-profile" data-ew-action="redirect" data-url="<?php echo $basePath; ?>userslist"><?php echo Language()->phrase("UserProfileTitle"); ?></a>
        {{if hasPersonalData}}
        <a class="dropdown-item" id="personal-data"{{props personalData}} data-{{:key}}="{{>prop}}"{{/props}}>{{:personalDataText}}</a>
        {{/if}}
        {{if canChangePassword}}
        <a class="dropdown-item" id="change-password"{{props changePassword}} data-{{:key}}="{{>prop}}"{{/props}}>{{:changePasswordText}}</a>
        {{/if}}
        {{if enable2FAText}}
        <a class="dropdown-item{{if !enable2FA}} d-none{{/if}}" id="enable-2fa" data-ew-action="enable-2fa">{{:enable2FAText}}</a>
        {{/if}}
        {{if backupCodes}}
        <a class="dropdown-item{{if !showBackupCodes}} d-none{{/if}}" id="backup-codes" data-ew-action="backup-codes">{{:backupCodes}}</a>
        {{/if}}
        {{if disable2FAText}}
        <a class="dropdown-item{{if !disable2FA}} d-none{{/if}}" id="disable-2fa" data-ew-action="disable-2fa">{{:disable2FAText}}</a>
        {{/if}}
        {{if canLogout}}
        <div class="dropdown-divider"></div>
        <div class="dropdown-footer text-end py-0">
            <a class="btn btn-default"{{props logout}} data-{{:key}}="{{>prop}}"{{/props}}>{{:logoutText}}</a>
        </div>
        {{/if}}
    </div>
</li>
{{else}}
    {{if canLogin}}
<li class="nav-item"><a class="nav-link ew-tooltip" title="{{:loginTitle}}"{{props login}} data-{{:key}}="{{>prop}}"{{/props}}>{{:loginText}}</a></li>
    {{/if}}
    {{if canLogout}}
<li class="nav-item"><a class="nav-link ew-tooltip"{{props logout}} data-{{:key}}="{{>prop}}"{{/props}}>{{:logoutText}}</a></li>
    {{/if}}
{{/if}}
</script>
<link rel="shortcut icon" type="image/x-icon" href="<?= BasePath() ?>/civichub.ico">
<link rel="icon" type="image/x-icon" href="<?= BasePath() ?>/civichub.ico">
<meta name="generator" content="PHPMaker 2022.11.0">
</head>
<body class="<?= Config("BODY_CLASS") ?>">
<?php if (@!$SkipHeaderFooter) { ?>
<div class="wrapper ew-layout">
    <!-- Main Header -->
    <!-- Navbar -->
    <nav class="<?= Config("NAVBAR_CLASS") ?>">
        <div class="container-fluid">
            <!-- Left navbar links -->
            <ul id="ew-navbar" class="navbar-nav">
                <li class="nav-item d-block">
                    <a class="nav-link" data-widget="pushmenu" data-enable-remember="true" data-ew-action="none"><i class="fas fa-bars ew-icon"></i></a>
                </li>
                <a class="navbar-brand d-none" href="../">
                    <span class="brand-text">CivicHub</span>
                </a>
            </ul>
            <!-- Right navbar links -->
            <ul id="ew-navbar-end" class="navbar-nav ms-auto"></ul>		
        </div>
    </nav>
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <aside class="<?= Config("SIDEBAR_CLASS") ?>">
        <div class="brand-container">
            <!-- Brand Logo //** Note: Only licensed users are allowed to change the logo ** -->
            <a href="../" class="brand-link">
                <span class="brand-text">CivicHub</span>
            </a>
        </div>
        <!-- Sidebar -->
        <div id="my-sidebar" class="sidebar">
            <!-- Sidebar user panel -->
            <!-- Sidebar Menu -->
            <nav id="ew-menu" class="mt-2"></nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
    <?php if (Config("PAGE_TITLE_STYLE") != "None") { ?>
            <div class="container-fluid">
                <div class="row">
                <?php if (!MS_SHOW_PHPMAKER_BREADCRUMBLINKS && !MS_SHOW_MASINO_BREADCRUMBLINKS) { ?>
				<div class="col-sm-12">
				<?php } else { ?>
                <div class="col-sm-6">
				<?php } ?>
                    <h1 class="m-0 text-dark"><?= CurrentPageHeading() ?> <small class="text-muted"><?= CurrentPageSubheading() ?></small>
					<?php if (MS_SHOW_HELP_ONLINE) { ?> 
					&nbsp;<a href='javascript:void(0);' id='helponline' onclick='getHelp("<?php echo CurrentPageName(); ?>");' title='<?php echo $Language->phrase("Help"); ?>'><span class='fa fa-question-circle ew-icon'></span></a> 
					 <?php } ?>
					</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <?php if (MS_SHOW_PHPMAKER_BREADCRUMBLINKS) { ?>
						<?php Breadcrumb()->render() ?>
					<?php } ?>
                </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
    <?php } ?>
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
        <div class="container-fluid">
<?php } ?>
<?= $content ?>
<?php if (@!$SkipHeaderFooter) { ?>
<?php
if (isset($DebugTimer)) {
    $DebugTimer->stop();
}
?>
        </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- ** Note: Only licensed users are allowed to change the copyright statement. ** -->
        <div class="ew-footer-text"><?= $Language->projectPhrase("FooterText") ?>&nbsp;|&nbsp;<span class="msTimer"></span>
		<div class="float-end d-none d-sm-inline">
		<?php if (MS_SHOW_ABOUT_US_ON_FOOTER) { ?> 
		&nbsp;|&nbsp;<a href='javascript:void(0);' class="kt-footer__menu-link kt-link" id='aboutus' onclick='getAboutUs();' title='<?php echo $Language->phrase("AboutUsTitle"); ?>'><?php echo $Language->phrase("AboutUsTitle"); ?></a> 
		<?php } ?>
		<?php if (MS_SHOW_TERMS_CONDITIONS_ON_FOOTER) { ?> 
		&nbsp;|&nbsp;<a href='javascript:void(0);' class="kt-footer__menu-link kt-link" id='termsandcond' onclick='getTermsConditions();' title='<?php echo $Language->phrase("TermsConditionsTitle"); ?>'><?php echo $Language->phrase("TermsConditionsTitle"); ?></a> 
		<?php } ?>
		</div>
		</div>
    </footer>
	<span class="scrollTop">
	  <svg height="40" width="40" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
		<path id="scrolltop-bg" d="M0 0h48v48h-48z"></path>
		<path id="scrolltop-arrow" d="M14.83 30.83l9.17-9.17 9.17 9.17 2.83-2.83-12-12-12 12z"></path>
	  </svg>
	</span>
</div>
<!-- ./wrapper -->
<?php } ?>
<script>
loadjs.done("wrapper");
</script>
<!-- template upload (for file upload) -->
<script id="template-upload" type="text/html">
{{for files}}
    <tr class="template-upload">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{{:name}}</p>
            <p class="error"></p>
        </td>
        <td>
            <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar bg-success" style="width: 0%;"></div></div>
        </td>
        <td>
            {{if !#index && !~root.options.autoUpload}}
            <button type="button" class="btn btn-default btn-sm start" disabled><?= $Language->phrase("UploadStart") ?></button>
            {{/if}}
            {{if !#index}}
            <button type="button" class="btn btn-default btn-sm cancel"><?= $Language->phrase("UploadCancel") ?></button>
            {{/if}}
        </td>
    </tr>
{{/for}}
</script>
<!-- template download (for file upload) -->
<script id="template-download" type="text/html">
{{for files}}
    <tr class="template-download">
        <td>
            <span class="preview">
                {{if !exists}}
                <span class="error"><?= $Language->phrase("FileNotFound") ?></span>
                {{else url && extension == "pdf"}}
                <div class="ew-pdfobject" data-url="{{>url}}" style="width: <?= Config("UPLOAD_THUMBNAIL_WIDTH") ?>px;"></div>
                {{else url && extension == "mp3"}}
                <audio controls><source type="audio/mpeg" src="{{>url}}"></audio>
                {{else url && extension == "mp4"}}
                <video controls><source type="video/mp4" src="{{>url}}"></video>
                {{else thumbnailUrl}}
                <a href="{{>url}}" title="{{>name}}" download="{{>name}}" class="ew-lightbox"><img class="ew-lazy" loading="lazy" src="{{>thumbnailUrl}}"></a>
                {{/if}}
            </span>
        </td>
        <td>
            <p class="name">
                {{if !exists}}
                <span class="text-muted">{{:name}}</span>
                {{else url && (extension == "pdf" || thumbnailUrl) && extension != "mp3" && extension != "mp4"}}
                <a href="{{>url}}" title="{{>name}}" data-extension="{{>extension}}" target="_blank">{{:name}}</a>
                {{else url}}
                <a href="{{>url}}" title="{{>name}}" data-extension="{{>extension}}" download="{{>name}}">{{:name}}</a>
                {{else}}
                <span>{{:name}}</span>
                {{/if}}
            </p>
            {{if error}}
            <div><span class="error">{{:error}}</span></div>
            {{/if}}
        </td>
        <td>
            <span class="size">{{:~root.formatFileSize(size)}}</span>
        </td>
        <td>
            {{if !~root.options.readonly && deleteUrl}}
            <button type="button" class="btn btn-default btn-sm delete" data-type="{{>deleteType}}" data-url="{{>deleteUrl}}"><?= $Language->phrase("UploadDelete") ?></button>
            {{else !~root.options.readonly}}
            <button type="button" class="btn btn-default btn-sm cancel"><?= $Language->phrase("UploadCancel") ?></button>
            {{/if}}
        </td>
    </tr>
{{/for}}
</script>
<!-- modal dialog -->
<div id="ew-modal-dialog" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog modal-fullscreen-sm-down"><div class="modal-content"><div class="modal-header"><h5 class="modal-title"></h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= $Language->phrase("CloseBtn") ?>"></div><div class="modal-body"></div><div class="modal-footer"></div></div></div></div>
<!-- add option dialog -->
<div id="ew-add-opt-dialog" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title"></h5></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary ew-btn"><?= $Language->phrase("AddBtn") ?></button><button type="button" class="btn btn-default ew-btn" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button></div></div></div></div>
<!-- import dialog -->
<div id="ew-import-dialog" class="modal" role="dialog" aria-hidden="true"><div class="modal-dialog modal-fullscreen-sm-down"><div class="modal-content"><div class="modal-header"><h5 class="modal-title"></h5></div>
<div class="modal-body">
    <div class="fileinput-button ew-file-drop-zone w-100">
        <input type="file" class="form-control ew-file-input" title="" id="importfiles" name="importfiles[]" multiple lang="<?= CurrentLanguageID() ?>">
        <div class="text-muted ew-file-text">Drop file here or click to upload</div>
    </div>
    <div class="message d-none mt-3"></div>
    <div class="progress d-none mt-3"><div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">0%</div></div>
</div>
<div class="modal-footer"><button type="button" class="btn btn-default ew-close-btn" data-bs-dismiss="modal"><?= $Language->phrase("CloseBtn") ?></button></div></div></div></div>
<!-- tooltip -->
<div id="ew-tooltip"></div>
<!-- drill down -->
<div id="ew-drilldown-panel"></div>
<script>
loadjs.done("wrapper");
</script>
<script>

function getHelp(strPage){$.ajax({url: ew.PATH_BASE + "loadhelponline",type: "GET",data: "page="+strPage,success: function (responseText) {var arrResponseText = responseText.split("~~~", 2); alertify.alert(arrResponseText[1]).set('title',arrResponseText[0]).set('modal',true).setting({'resizable':true,'maximizable':true}).resizeTo(340,500).set('onok',function(closeEvent){alertify.alert().destroy();});}});}function getAboutUs(){$.ajax({url: ew.PATH_BASE + "loadaboutus",type: "GET",success: function (responseText) {alertify.alert(responseText).set('title','<?php echo $Language->phrase("AboutUsTitle") ?>').set('modal',true).setting({'resizable':true,'maximizable':true}).resizeTo(340,480).set('onok',function(closeEvent){alertify.alert().destroy();});}});}function getTermsConditions(){$.ajax({url: ew.PATH_BASE + "loadtermsconditions",type: "GET",success: function (responseText) {alertify.alert(responseText).set('title','<?php echo $Language->phrase("TermsConditionsTitle") ?>').set('modal',true).setting({'resizable':true,'maximizable':true}).resizeTo(340,500).set('onok',function(closeEvent){alertify.alert().destroy();});}});}
</script>
<script>
loadjs.ready(ew.bundleIds, function() {
  if (!loadjs.isDefined("foot")) loadjs.done("foot");
  if (window.innerWidth <= 990 || $(window).width() <= 990)
     $('[data-widget="pushmenu"]').PushMenu("collapse");
  window.onresize = SidebarHandling;

  function SidebarHandling() {
    if (window.innerWidth <= 990 || $(window).width() <= 990)
      $('[data-widget="pushmenu"]').PushMenu("collapse");
  }
  <?php if (Language()->Type == "SELECT") { ?>
  loadjs.ready("msdropdown", function(){ $("#ew-language").msDropdown();});
  <?php } ?>
  var osInstance = OverlayScrollbars(document.getElementById("my-sidebar"), {className:"os-theme-light", callbacks: {	onScrollStop: function(e) {const scrollInfo = osInstance.scroll();	const topPosition = e.target.scrollTop;	var expires = new Date(new Date().getTime() + 525600 * 60 * 1000); Cookies.set('menuTopPosition', topPosition, { path: '', expires: expires });	}}}); var menuTopPosition = Cookies.get('menuTopPosition') > 0 ? Cookies.get('menuTopPosition') : "0"; osInstance.scroll({ y: menuTopPosition }, 900); document.addEventListener("scroll", handleScroll);var scrollTop = document.querySelector(".scrollTop");

  function handleScroll() { var scrollableHeight = document.documentElement.scrollHeight -document.documentElement.clientHeight; var golden_ratio = 0.4; if ((document.documentElement.scrollTop / scrollableHeight ) > golden_ratio) { scrollTop.style.display = "block"; } else { scrollTop.style.display = "none"; }}scrollTop.addEventListener("click", scrollToTop); function scrollToTop() { window.scrollTo({ top: 0, behavior: "smooth"});}
});
</script>
</body>
</html>
