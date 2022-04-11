<?php

namespace PHPMaker2022\civichub2;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

// Handle Routes
return function (App $app) {
	// breadcrumblinksaddsp
    $app->any('/breadcrumblinksaddsp', BreadcrumblinksaddspController::class)->add(PermissionMiddleware::class)->setName('breadcrumblinksaddsp-breadcrumblinksaddsp-custom'); // custom

	// breadcrumblinkschecksp
    $app->any('/breadcrumblinkschecksp', BreadcrumblinkscheckspController::class)->add(PermissionMiddleware::class)->setName('breadcrumblinkschecksp-breadcrumblinkschecksp-custom'); // custom

	// breadcrumblinksdeletesp
    $app->any('/breadcrumblinksdeletesp', BreadcrumblinksdeletespController::class)->add(PermissionMiddleware::class)->setName('breadcrumblinksdeletesp-breadcrumblinksdeletesp-custom'); // custom

	// breadcrumblinksmovesp
    $app->any('/breadcrumblinksmovesp', BreadcrumblinksmovespController::class)->add(PermissionMiddleware::class)->setName('breadcrumblinksmovesp-breadcrumblinksmovesp-custom'); // custom

	// calendarscheduler
    $app->any('/calendarscheduler', CalendarschedulerController::class)->add(PermissionMiddleware::class)->setName('calendarscheduler-calendarscheduler-custom'); // custom

	// loadhelponline
    $app->any('/loadhelponline', LoadhelponlineController::class)->add(PermissionMiddleware::class)->setName('loadhelponline-loadhelponline-custom'); // custom

	// loadaboutus
    $app->any('/loadaboutus', LoadaboutusController::class)->add(PermissionMiddleware::class)->setName('loadaboutus-loadaboutus-custom'); // custom

	// loadtermsconditions
    $app->any('/loadtermsconditions', LoadtermsconditionsController::class)->add(PermissionMiddleware::class)->setName('loadtermsconditions-loadtermsconditions-custom'); // custom

	// printtermsconditions
    $app->any('/printtermsconditions', PrinttermsconditionsController::class)->add(PermissionMiddleware::class)->setName('printtermsconditions-printtermsconditions-custom'); // custom

    // submission_view2
    $app->map(["GET","POST","OPTIONS"], '/submissionview2list[/{Submission_ID}]', SubmissionView2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('submissionview2list-submission_view2-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/submissionview2add[/{Submission_ID}]', SubmissionView2Controller::class . ':add')->add(PermissionMiddleware::class)->setName('submissionview2add-submission_view2-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/submissionview2view[/{Submission_ID}]', SubmissionView2Controller::class . ':view')->add(PermissionMiddleware::class)->setName('submissionview2view-submission_view2-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/submissionview2edit[/{Submission_ID}]', SubmissionView2Controller::class . ':edit')->add(PermissionMiddleware::class)->setName('submissionview2edit-submission_view2-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/submissionview2delete[/{Submission_ID}]', SubmissionView2Controller::class . ':delete')->add(PermissionMiddleware::class)->setName('submissionview2delete-submission_view2-delete'); // delete
    $app->group(
        '/submission_view2',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{Submission_ID}]', SubmissionView2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('submission_view2/list-submission_view2-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{Submission_ID}]', SubmissionView2Controller::class . ':add')->add(PermissionMiddleware::class)->setName('submission_view2/add-submission_view2-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{Submission_ID}]', SubmissionView2Controller::class . ':view')->add(PermissionMiddleware::class)->setName('submission_view2/view-submission_view2-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{Submission_ID}]', SubmissionView2Controller::class . ':edit')->add(PermissionMiddleware::class)->setName('submission_view2/edit-submission_view2-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{Submission_ID}]', SubmissionView2Controller::class . ':delete')->add(PermissionMiddleware::class)->setName('submission_view2/delete-submission_view2-delete-2'); // delete
        }
    );

    // submission_comments
    $app->map(["GET","POST","OPTIONS"], '/submissioncommentslist[/{Comment_ID}]', SubmissionCommentsController::class . ':list')->add(PermissionMiddleware::class)->setName('submissioncommentslist-submission_comments-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/submissioncommentsadd[/{Comment_ID}]', SubmissionCommentsController::class . ':add')->add(PermissionMiddleware::class)->setName('submissioncommentsadd-submission_comments-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/submissioncommentsedit[/{Comment_ID}]', SubmissionCommentsController::class . ':edit')->add(PermissionMiddleware::class)->setName('submissioncommentsedit-submission_comments-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/submissioncommentsdelete[/{Comment_ID}]', SubmissionCommentsController::class . ':delete')->add(PermissionMiddleware::class)->setName('submissioncommentsdelete-submission_comments-delete'); // delete
    $app->group(
        '/submission_comments',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{Comment_ID}]', SubmissionCommentsController::class . ':list')->add(PermissionMiddleware::class)->setName('submission_comments/list-submission_comments-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{Comment_ID}]', SubmissionCommentsController::class . ':add')->add(PermissionMiddleware::class)->setName('submission_comments/add-submission_comments-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{Comment_ID}]', SubmissionCommentsController::class . ':edit')->add(PermissionMiddleware::class)->setName('submission_comments/edit-submission_comments-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{Comment_ID}]', SubmissionCommentsController::class . ':delete')->add(PermissionMiddleware::class)->setName('submission_comments/delete-submission_comments-delete-2'); // delete
        }
    );

    // submission_vote
    $app->map(["GET","POST","OPTIONS"], '/submissionvotelist[/{keys:.*}]', SubmissionVoteController::class . ':list')->add(PermissionMiddleware::class)->setName('submissionvotelist-submission_vote-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/submissionvoteadd[/{keys:.*}]', SubmissionVoteController::class . ':add')->add(PermissionMiddleware::class)->setName('submissionvoteadd-submission_vote-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/submissionvoteview[/{keys:.*}]', SubmissionVoteController::class . ':view')->add(PermissionMiddleware::class)->setName('submissionvoteview-submission_vote-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/submissionvoteedit[/{keys:.*}]', SubmissionVoteController::class . ':edit')->add(PermissionMiddleware::class)->setName('submissionvoteedit-submission_vote-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/submissionvotedelete[/{keys:.*}]', SubmissionVoteController::class . ':delete')->add(PermissionMiddleware::class)->setName('submissionvotedelete-submission_vote-delete'); // delete
    $app->group(
        '/submission_vote',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{keys:.*}]', SubmissionVoteController::class . ':list')->add(PermissionMiddleware::class)->setName('submission_vote/list-submission_vote-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{keys:.*}]', SubmissionVoteController::class . ':add')->add(PermissionMiddleware::class)->setName('submission_vote/add-submission_vote-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{keys:.*}]', SubmissionVoteController::class . ':view')->add(PermissionMiddleware::class)->setName('submission_vote/view-submission_vote-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{keys:.*}]', SubmissionVoteController::class . ':edit')->add(PermissionMiddleware::class)->setName('submission_vote/edit-submission_vote-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{keys:.*}]', SubmissionVoteController::class . ':delete')->add(PermissionMiddleware::class)->setName('submission_vote/delete-submission_vote-delete-2'); // delete
        }
    );

    // submission_monitor
    $app->map(["GET","POST","OPTIONS"], '/submissionmonitorlist[/{Monitor_ID}]', SubmissionMonitorController::class . ':list')->add(PermissionMiddleware::class)->setName('submissionmonitorlist-submission_monitor-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/submissionmonitoradd[/{Monitor_ID}]', SubmissionMonitorController::class . ':add')->add(PermissionMiddleware::class)->setName('submissionmonitoradd-submission_monitor-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/submissionmonitorview[/{Monitor_ID}]', SubmissionMonitorController::class . ':view')->add(PermissionMiddleware::class)->setName('submissionmonitorview-submission_monitor-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/submissionmonitoredit[/{Monitor_ID}]', SubmissionMonitorController::class . ':edit')->add(PermissionMiddleware::class)->setName('submissionmonitoredit-submission_monitor-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/submissionmonitordelete[/{Monitor_ID}]', SubmissionMonitorController::class . ':delete')->add(PermissionMiddleware::class)->setName('submissionmonitordelete-submission_monitor-delete'); // delete
    $app->group(
        '/submission_monitor',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{Monitor_ID}]', SubmissionMonitorController::class . ':list')->add(PermissionMiddleware::class)->setName('submission_monitor/list-submission_monitor-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{Monitor_ID}]', SubmissionMonitorController::class . ':add')->add(PermissionMiddleware::class)->setName('submission_monitor/add-submission_monitor-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{Monitor_ID}]', SubmissionMonitorController::class . ':view')->add(PermissionMiddleware::class)->setName('submission_monitor/view-submission_monitor-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{Monitor_ID}]', SubmissionMonitorController::class . ':edit')->add(PermissionMiddleware::class)->setName('submission_monitor/edit-submission_monitor-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{Monitor_ID}]', SubmissionMonitorController::class . ':delete')->add(PermissionMiddleware::class)->setName('submission_monitor/delete-submission_monitor-delete-2'); // delete
        }
    );

    // userlevelpermissions
    $app->map(["GET","POST","OPTIONS"], '/userlevelpermissionslist[/{keys:.*}]', UserlevelpermissionsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevelpermissionslist-userlevelpermissions-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/userlevelpermissionsadd[/{keys:.*}]', UserlevelpermissionsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevelpermissionsadd-userlevelpermissions-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/userlevelpermissionsview[/{keys:.*}]', UserlevelpermissionsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevelpermissionsview-userlevelpermissions-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/userlevelpermissionsedit[/{keys:.*}]', UserlevelpermissionsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevelpermissionsedit-userlevelpermissions-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/userlevelpermissionsdelete[/{keys:.*}]', UserlevelpermissionsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevelpermissionsdelete-userlevelpermissions-delete'); // delete
    $app->group(
        '/userlevelpermissions',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{keys:.*}]', UserlevelpermissionsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevelpermissions/list-userlevelpermissions-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{keys:.*}]', UserlevelpermissionsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevelpermissions/add-userlevelpermissions-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{keys:.*}]', UserlevelpermissionsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevelpermissions/view-userlevelpermissions-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{keys:.*}]', UserlevelpermissionsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevelpermissions/edit-userlevelpermissions-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{keys:.*}]', UserlevelpermissionsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevelpermissions/delete-userlevelpermissions-delete-2'); // delete
        }
    );

    // userlevels
    $app->map(["GET","POST","OPTIONS"], '/userlevelslist[/{User_Level_ID}]', UserlevelsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevelslist-userlevels-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/userlevelsadd[/{User_Level_ID}]', UserlevelsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevelsadd-userlevels-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/userlevelsview[/{User_Level_ID}]', UserlevelsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevelsview-userlevels-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/userlevelsedit[/{User_Level_ID}]', UserlevelsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevelsedit-userlevels-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/userlevelsdelete[/{User_Level_ID}]', UserlevelsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevelsdelete-userlevels-delete'); // delete
    $app->group(
        '/userlevels',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{User_Level_ID}]', UserlevelsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevels/list-userlevels-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{User_Level_ID}]', UserlevelsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevels/add-userlevels-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{User_Level_ID}]', UserlevelsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevels/view-userlevels-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{User_Level_ID}]', UserlevelsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevels/edit-userlevels-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{User_Level_ID}]', UserlevelsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevels/delete-userlevels-delete-2'); // delete
        }
    );

    // users
    $app->map(["GET","POST","OPTIONS"], '/userslist[/{_Username:.*}]', UsersController::class . ':list')->add(PermissionMiddleware::class)->setName('userslist-users-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/usersadd[/{_Username:.*}]', UsersController::class . ':add')->add(PermissionMiddleware::class)->setName('usersadd-users-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/usersview[/{_Username:.*}]', UsersController::class . ':view')->add(PermissionMiddleware::class)->setName('usersview-users-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/usersedit[/{_Username:.*}]', UsersController::class . ':edit')->add(PermissionMiddleware::class)->setName('usersedit-users-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/usersdelete[/{_Username:.*}]', UsersController::class . ':delete')->add(PermissionMiddleware::class)->setName('usersdelete-users-delete'); // delete
    $app->group(
        '/users',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{_Username:.*}]', UsersController::class . ':list')->add(PermissionMiddleware::class)->setName('users/list-users-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{_Username:.*}]', UsersController::class . ':add')->add(PermissionMiddleware::class)->setName('users/add-users-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{_Username:.*}]', UsersController::class . ':view')->add(PermissionMiddleware::class)->setName('users/view-users-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{_Username:.*}]', UsersController::class . ':edit')->add(PermissionMiddleware::class)->setName('users/edit-users-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{_Username:.*}]', UsersController::class . ':delete')->add(PermissionMiddleware::class)->setName('users/delete-users-delete-2'); // delete
        }
    );

    // ref_category
    $app->map(["GET","POST","OPTIONS"], '/refcategorylist[/{Category_ID}]', RefCategoryController::class . ':list')->add(PermissionMiddleware::class)->setName('refcategorylist-ref_category-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/refcategoryadd[/{Category_ID}]', RefCategoryController::class . ':add')->add(PermissionMiddleware::class)->setName('refcategoryadd-ref_category-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/refcategoryaddopt', RefCategoryController::class . ':addopt')->add(PermissionMiddleware::class)->setName('refcategoryaddopt-ref_category-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/refcategoryview[/{Category_ID}]', RefCategoryController::class . ':view')->add(PermissionMiddleware::class)->setName('refcategoryview-ref_category-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/refcategoryedit[/{Category_ID}]', RefCategoryController::class . ':edit')->add(PermissionMiddleware::class)->setName('refcategoryedit-ref_category-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/refcategorydelete[/{Category_ID}]', RefCategoryController::class . ':delete')->add(PermissionMiddleware::class)->setName('refcategorydelete-ref_category-delete'); // delete
    $app->group(
        '/ref_category',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{Category_ID}]', RefCategoryController::class . ':list')->add(PermissionMiddleware::class)->setName('ref_category/list-ref_category-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{Category_ID}]', RefCategoryController::class . ':add')->add(PermissionMiddleware::class)->setName('ref_category/add-ref_category-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADDOPT_ACTION") . '', RefCategoryController::class . ':addopt')->add(PermissionMiddleware::class)->setName('ref_category/addopt-ref_category-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{Category_ID}]', RefCategoryController::class . ':view')->add(PermissionMiddleware::class)->setName('ref_category/view-ref_category-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{Category_ID}]', RefCategoryController::class . ':edit')->add(PermissionMiddleware::class)->setName('ref_category/edit-ref_category-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{Category_ID}]', RefCategoryController::class . ':delete')->add(PermissionMiddleware::class)->setName('ref_category/delete-ref_category-delete-2'); // delete
        }
    );

    // ref_country
    $app->map(["GET","POST","OPTIONS"], '/refcountrylist', RefCountryController::class . ':list')->add(PermissionMiddleware::class)->setName('refcountrylist-ref_country-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/refcountryadd', RefCountryController::class . ':add')->add(PermissionMiddleware::class)->setName('refcountryadd-ref_country-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/refcountryaddopt', RefCountryController::class . ':addopt')->add(PermissionMiddleware::class)->setName('refcountryaddopt-ref_country-addopt'); // addopt
    $app->group(
        '/ref_country',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '', RefCountryController::class . ':list')->add(PermissionMiddleware::class)->setName('ref_country/list-ref_country-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '', RefCountryController::class . ':add')->add(PermissionMiddleware::class)->setName('ref_country/add-ref_country-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADDOPT_ACTION") . '', RefCountryController::class . ':addopt')->add(PermissionMiddleware::class)->setName('ref_country/addopt-ref_country-addopt-2'); // addopt
        }
    );

    // ref_organisation
    $app->map(["GET","POST","OPTIONS"], '/reforganisationlist', RefOrganisationController::class . ':list')->add(PermissionMiddleware::class)->setName('reforganisationlist-ref_organisation-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/reforganisationadd', RefOrganisationController::class . ':add')->add(PermissionMiddleware::class)->setName('reforganisationadd-ref_organisation-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/reforganisationaddopt', RefOrganisationController::class . ':addopt')->add(PermissionMiddleware::class)->setName('reforganisationaddopt-ref_organisation-addopt'); // addopt
    $app->group(
        '/ref_organisation',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '', RefOrganisationController::class . ':list')->add(PermissionMiddleware::class)->setName('ref_organisation/list-ref_organisation-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '', RefOrganisationController::class . ':add')->add(PermissionMiddleware::class)->setName('ref_organisation/add-ref_organisation-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADDOPT_ACTION") . '', RefOrganisationController::class . ':addopt')->add(PermissionMiddleware::class)->setName('ref_organisation/addopt-ref_organisation-addopt-2'); // addopt
        }
    );

    // ref_sdg
    $app->map(["GET","POST","OPTIONS"], '/refsdglist[/{Goal_Number}]', RefSdgController::class . ':list')->add(PermissionMiddleware::class)->setName('refsdglist-ref_sdg-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/refsdgadd[/{Goal_Number}]', RefSdgController::class . ':add')->add(PermissionMiddleware::class)->setName('refsdgadd-ref_sdg-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/refsdgview[/{Goal_Number}]', RefSdgController::class . ':view')->add(PermissionMiddleware::class)->setName('refsdgview-ref_sdg-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/refsdgedit[/{Goal_Number}]', RefSdgController::class . ':edit')->add(PermissionMiddleware::class)->setName('refsdgedit-ref_sdg-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/refsdgdelete[/{Goal_Number}]', RefSdgController::class . ':delete')->add(PermissionMiddleware::class)->setName('refsdgdelete-ref_sdg-delete'); // delete
    $app->group(
        '/ref_sdg',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{Goal_Number}]', RefSdgController::class . ':list')->add(PermissionMiddleware::class)->setName('ref_sdg/list-ref_sdg-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{Goal_Number}]', RefSdgController::class . ':add')->add(PermissionMiddleware::class)->setName('ref_sdg/add-ref_sdg-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{Goal_Number}]', RefSdgController::class . ':view')->add(PermissionMiddleware::class)->setName('ref_sdg/view-ref_sdg-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{Goal_Number}]', RefSdgController::class . ':edit')->add(PermissionMiddleware::class)->setName('ref_sdg/edit-ref_sdg-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{Goal_Number}]', RefSdgController::class . ':delete')->add(PermissionMiddleware::class)->setName('ref_sdg/delete-ref_sdg-delete-2'); // delete
        }
    );

    // vote_tally
    $app->map(["GET","POST","OPTIONS"], '/votetallylist[/{Submission_ID}]', VoteTallyController::class . ':list')->add(PermissionMiddleware::class)->setName('votetallylist-vote_tally-list'); // list
    $app->group(
        '/vote_tally',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{Submission_ID}]', VoteTallyController::class . ':list')->add(PermissionMiddleware::class)->setName('vote_tally/list-vote_tally-list-2'); // list
        }
    );

    // Voting
    $app->map(["GET", "POST", "OPTIONS"], '/voting', VotingController::class)->add(PermissionMiddleware::class)->setName('voting-Voting-summary'); // summary

    // submission
    $app->map(["GET","POST","OPTIONS"], '/submissionlist[/{Submission_ID}]', SubmissionController::class . ':list')->add(PermissionMiddleware::class)->setName('submissionlist-submission-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/submissionview[/{Submission_ID}]', SubmissionController::class . ':view')->add(PermissionMiddleware::class)->setName('submissionview-submission-view'); // view
    $app->group(
        '/submission',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{Submission_ID}]', SubmissionController::class . ':list')->add(PermissionMiddleware::class)->setName('submission/list-submission-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{Submission_ID}]', SubmissionController::class . ':view')->add(PermissionMiddleware::class)->setName('submission/view-submission-view-2'); // view
        }
    );

    // error
    $app->map(["GET","POST","OPTIONS"], '/error', OthersController::class . ':error')->add(PermissionMiddleware::class)->setName('error');

    // personal_data
    $app->map(["GET","POST","OPTIONS"], '/personaldata', OthersController::class . ':personaldata')->add(PermissionMiddleware::class)->setName('personaldata');

    // login
    $app->map(["GET","POST","OPTIONS"], '/login', OthersController::class . ':login')->add(PermissionMiddleware::class)->setName('login');

    // reset_password
    $app->map(["GET","POST","OPTIONS"], '/resetpassword', OthersController::class . ':resetpassword')->add(PermissionMiddleware::class)->setName('resetpassword');

    // change_password
    $app->map(["GET","POST","OPTIONS"], '/changepassword', OthersController::class . ':changepassword')->add(PermissionMiddleware::class)->setName('changepassword');

    // register
    $app->map(["GET","POST","OPTIONS"], '/register', OthersController::class . ':register')->add(PermissionMiddleware::class)->setName('register');

    // userpriv
    $app->map(["GET","POST","OPTIONS"], '/userpriv', OthersController::class . ':userpriv')->add(PermissionMiddleware::class)->setName('userpriv');

    // logout
    $app->map(["GET","POST","OPTIONS"], '/logout', OthersController::class . ':logout')->add(PermissionMiddleware::class)->setName('logout');

    // Swagger
    $app->get('/' . Config("SWAGGER_ACTION"), OthersController::class . ':swagger')->setName(Config("SWAGGER_ACTION")); // Swagger

    // Index
    $app->get('/[index]', OthersController::class . ':index')->add(PermissionMiddleware::class)->setName('index');

    // Route Action event
    if (function_exists(PROJECT_NAMESPACE . "Route_Action")) {
        Route_Action($app);
    }

    /**
     * Catch-all route to serve a 404 Not Found page if none of the routes match
     * NOTE: Make sure this route is defined last.
     */
    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        function ($request, $response, $params) {
            $error = [
                "statusCode" => "404",
                "error" => [
                    "class" => "text-warning",
                    "type" => Container("language")->phrase("Error"),
                    "description" => str_replace("%p", $params["routes"], Container("language")->phrase("PageNotFound")),
                ],
            ];
            Container("flash")->addMessage("error", $error);
            return $response->withStatus(302)->withHeader("Location", GetUrl("error")); // Redirect to error page
        }
    );
};
