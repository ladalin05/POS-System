<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>POS Module | Wincodetech</title>
    <script type="text/javascript">if (parent.frames.length !== 0) { top.location = ''; }</script>
    <meta name="viewport" content="user-scalable=no" />
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="pragma" content="no-cache" />
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.jpg') }}" />
    <link rel="stylesheet" href="{{ asset('assets/styles/theme.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/styles/style.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/pos/css/posajax.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/pos/css/print.css') }}" type="text/css" media="print" />
    <script type="text/javascript" src="{{ asset('assets/js_pos/jquery-2.0.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js_pos/jquery-migrate-1.2.1.min.js') }}"></script>

</head>



<body>
    <style>
        #categories-wrapper {
            overflow: hidden;
            white-space: nowrap;
            width: 100%;
        }

        /* inner holds all category buttons inline */
        #categories-inner {
            display: inline-block;
            transition: transform 300ms ease;
            will-change: transform;
        }

        /* make buttons inline and sized consistently */
        #categories-inner .category {
            display: inline-block;
            margin: 6px 8px;
            padding: 10px 14px;
            min-width: 120px;
            /* adjust width to fit 5 in your layout */
            box-sizing: border-box;
            white-space: normal;
            vertical-align: middle;
        }

        /* active visual */
        .category.active {
            outline: 3px solid #720018;
            background-color: #2eb8bd;
        }

        #item-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 15px;
            padding: 10px;
            max-height: 650px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #888 #f1f1f1;
            box-sizing: border-box;
        }

        /* Product buttons layout */
        #item-list .product {
            box-sizing: border-box;
            /* include padding/border in width calc */
            flex: 0 0 calc(20% - 12px);
            /* 5 items per row (adjust gap) */
            min-width: 140px;
            /* avoid squishing on small screens */
            max-width: 220px;
            /* optional cap */
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            text-align: center;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all .2s ease-in-out;
        }

        #item-list .product:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        #item-list .product img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        #item-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(189px, 22fr));
            grid-gap: 12px;
            padding: 10px;
        }

        /* Product Button Style */
        .btn-prni {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            text-align: center;
            border: none;
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.25s ease-in-out;
            cursor: pointer;
            padding: 8px;
            height: 260px;
            width: 200px;
        }

        /* Product Image */
        .btn-prni img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 8px;
            background: #f8f8f8;
        }

        /* Product Name Text */
        .btn-prni span {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            line-height: 1.2;
            white-space: normal;
        }

        /* Hover Effects */
        .btn-prni:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            background-color: #f3f9ff;
        }

        /* Active (when clicked) */
        .btn-prni:active {
            transform: scale(0.98);
        }

        /* Tooltip title support */
        .btn-prni[title] {
            cursor: pointer;
        }

        /* Optional: show selected category in red highlight */
        .btn-prni.active {
            border: 2px solid #b30000;
            background-color: #ffeaea;
        }
    </style>
    <noscript>
        <div class="global-site-notice noscript">
            <div class="notice-inner">
                <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled
                    in
                    your browser to utilize the functionality of this website.</p>
            </div>
        </div>
    </noscript>

    <div id="wrapper">
        <header id="header" class="navbar">
            <div class="container">
                <a class="navbar-brand" href="">
                    <span class="logo">
                        <span class="pos-logo-lg">Wincodetech</span>
                        <span class="pos-logo-sm">POS</span>
                    </span>
                </a>

                <div class="header-nav">
                    <ul class="nav navbar-nav pull-right">
                        <li class="dropdown">
                            <a class="account dropdown-toggle" data-toggle="dropdown" href="#">
                                <div class="user">
                                    <span>Wincodetech</span>
                                </div>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="{{ route('settings.users-management.users.account') }}">
                                        <i class="fa fa-user"></i> Profile </a>
                                </li>

                                <li class="divider"></li>
                                <li>
                                    <a href="javascript:void(0)" onclick="logout()">
                                        <i class="fa fa-sign-out"></i> Logout
                                    </a>
                                </li>

                            </ul>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav pull-right">
                        <li class="dropdown">
                            <a class=" pos-tip" title="Dashboard" data-placement="bottom" href="dashboard">
                                <i class="fa fa-dashboard"></i>
                            </a>
                        </li>
                        <input type="hidden" class="sp_favorite" />
                        <li class="dropdown hidden">
                            <a class="pos-tip" title="Shortcuts" data-placement="bottom" href="#" data-toggle="modal"
                                data-target="#sckModal">
                                <i class="fa fa-key"></i>
                            </a>
                        </li>
                        <li class="dropdown hidden">
                            <a class="pos-tip" title="View Bill Screen" data-placement="bottom" href="pos/view_bill"
                                target="_blank">
                                <i class="fa fa-laptop"></i>
                            </a>
                        </li>

                        <li>
                            <a class="pos-tip cl-danger" href="{{ route('pos.table.addTable') }}"
                                style="color:#ffffff;font-weight:bold;">

                                @if(!empty($selectedRoomName))
                                    {{ $selectedRoomName }}
                                @else
                                    <i class="fa fa-toggle-off" aria-hidden="true"></i>
                                @endif
                            </a>
                        </li>
                        <input type="hidden" id="posroom" value="{{ $selectedRoomId ?? '' }}">


                        <li class="dropdown">
                            <a class="pos-tip" id="opened_bills" title="{{ __('Suspended Sales') }}"
                                data-placement="bottom" data-html="true" href="{{ route('pos.opened_bills') }}"
                                data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-th"></i>
                            </a>
                        </li>


                        <li class="dropdown">
                            <a class="pos-tip" id="register_items" title="Register Products" data-placement="bottom"
                                data-html="true" href="pos/register_items" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-folder-open-o"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a class="pos-tip" id="register_details" title="Register Details" data-placement="bottom"
                                data-html="true" href="pos/register_details" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a class="pos-tip" id="close_register" title="Close Register" data-placement="bottom"
                                data-html="true" data-backdrop="static" href="pos/close_register" data-toggle="modal"
                                data-target="#myModal">
                                <i class="fa fa-times-circle"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a class="pos-tip" id="add_expense" title="Add Expense" href="expense/add_expense/add">
                                <i class="fa fa-dollar"></i>
                            </a>
                        </li>


                        <li class="dropdown">
                            <a class="pos-tip" id="today_sale" title="Today's Sale" data-placement="bottom"
                                data-html="true" href="{{ route('pos.today_sale') }}" data-toggle="modal"
                                data-target="#myModal">
                                <i class="fa fa-file-text"></i>
                            </a>
                        </li>

                        <li class="dropdown hidden-xs">
                            <a class="pos-tip" title="List Open Registers" data-placement="bottom" href="pos/registers">
                                <i class="fa fa-list"></i>
                                <span class="posnumber white">4</span> </a>
                        </li>
                        <li class="dropdown hidden-xs">
                            <a class="pos-tip" title="Clear all locally saved data" data-placement="bottom" id="clearLS"
                                href="#">
                                <i class="fa fa-eraser"></i>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav pull-right">
                        <li class="dropdown">
                            <a style="cursor: default;"><span id="display_time"></span></a>
                        </li>
                    </ul>


                </div>



            </div>
        </header>

        <div id="content">
            <div class="c1">
                <div class="pos">

                    <div id="pos">
                        <form action="pos" data-toggle="validator" role="form" id="pos-sale-form" method="post"
                            accept-charset="utf-8">
                            <input type="hidden" name="token" value="c3f7475eec476116172e42ccd52f7b13" />

                            <input type="hidden" name="suspend_bill_id" value="">
                            <input type="hidden" name="sale_man_id" id='sale_man_id'>
                            <div class="col-xs-6">

                                <div id="printhead">
                                    <h4 style="text-transform:uppercase;">Mealea Spa</h4>
                                    <h5 style="text-transform:uppercase;">Order List</h5>Date 06/08/2025 13:02
                                </div>
                                <div id="left-top">
                                    <div style="position: absolute; left:-9999px;"><input type="text" name="test"
                                            value="" id="test" class="kb-pad" />
                                    </div>

                                    <input type="hidden" name="biller" id="biller" value="3" />
                                    <input type="hidden" name="project" id="project_id" />
                                    <input type="hidden" name="delivery_status" id="delivery_status" />
                                    <input type="hidden" name="reference_no" id="pos_reference_no" />
                                    <input type="hidden" name="date" id="podate" value="06/08/2025 13:02:42" />


                                    <div class="form-group">
                                        <div class="input-group">

                                            <input type="text" name="customer" value="" id="poscustomer"
                                                data-placeholder="Select Customer" required="required"
                                                class="form-control pos-input-tip" style="width:100%;" />

                                            <input type="hidden" name="saleman_id" id="saleman_id" />

                                            <div class="input-group-addon no-print"
                                                style="padding: 2px 8px; border-left: 0;">
                                                <a href="#" id="toogle-customer-read-attr" class="external">
                                                    <i class="fa fa-pencil" id="addIcon" style="font-size: 1.2em;"></i>
                                                </a>
                                            </div>
                                            <div class="input-group-addon no-print"
                                                style="padding: 2px 7px; border-left: 0;">
                                                <a href="#" id="view-customer" class="external" data-toggle="modal"
                                                    data-target="#myModal">
                                                    <i class="fa fa-eye" id="addIcon" style="font-size: 1.2em;"></i>
                                                </a>
                                            </div>
                                            <div class="input-group-addon no-print" style="padding: 2px 8px;">
                                                <a href="customers/add" id="add-customer" class="external"
                                                    data-toggle="modal" data-target="#myModal">
                                                    <i class="fa fa-plus-circle" id="addIcon"
                                                        style="font-size: 1.5em;"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </div>

                                    <div class="no-print">

                                        <div class="form-group">
                                            <select name="warehouse" id="poswarehouse"
                                                class="form-control pos-input-tip" data-placeholder="Select Warehouse"
                                                required="required" style="width:100%;">
                                                <option value="1" selected="selected">Head Office</option>
                                            </select>
                                        </div>

                                        <div class="form-group" id="ui">
                                            <div class="input-group">
                                                <input type="text" name="add_item" value="" class="form-control pos-tip"
                                                    id="add_item" data-placement="top" data-trigger="focus"
                                                    placeholder="Scan/Search product by name/code"
                                                    title="Please start typing code/name for suggestions or just scan barcode" />
                                                <div class="input-group-addon" style="padding: 2px 8px;">
                                                    <a href="#" id="addManually">
                                                        <i class="fa fa-plus-circle" id="addIcon"
                                                            style="font-size: 1.5em;"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div style="clear:both;"></div>
                                        </div>

                                    </div>
                                </div>
                                <div id="print">
                                    <div id="left-middle">
                                        <div id="product-list">
                                            <table
                                                class="table items table-striped table-bordered table-condensed table-hover sortable_table"
                                                id="posTable" style="margin-bottom: 0;">
                                                <thead>
                                                    <tr>
                                                        <th width="40%">Product</th>
                                                        <th width="15%">Product unit</th>
                                                        <th width="15%">Price</th>
                                                        <th width="15%">Qty</th>
                                                        <th width="15%">Subtotal</th>
                                                        <th style="width: 5%; text-align: center;">
                                                            <i class="fa fa-trash-o"
                                                                style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                            <div style="clear:both;"></div>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div id="left-bottom">
                                        <table id="totalTable"
                                            style="width:100%; float:right; padding:5px; color:#000; background: #FFF;">
                                            <tr>
                                                <td style="padding: 5px 10px;border-top: 1px solid #DDD;">Items</td>
                                                <td class="text-right"
                                                    style="padding: 5px 10px;font-size: 14px; font-weight:bold;border-top: 1px solid #DDD;">
                                                    <span id="titems">0</span>
                                                </td>
                                                <td style="padding: 5px 10px;border-top: 1px solid #DDD;">Total</td>
                                                <td class="text-right"
                                                    style="padding: 5px 10px;font-size: 14px; font-weight:bold;border-top: 1px solid #DDD;">
                                                    <span id="total">0.00</span>
                                                </td>
                                            </tr>
                                            <tr>

                                                <td style="padding: 5px 10px;">Order Tax <a href="#" id="pptax2">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>

                                                <td class="text-right"
                                                    style="padding: 5px 10px;font-size: 14px; font-weight:bold;">
                                                    <span id="ttax2">0.00</span>
                                                </td>
                                                <td style="padding: 5px 10px;">Discount <a href="#" id="ppdiscount">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                                <td class="text-right" style="padding: 5px 10px;font-weight:bold;">
                                                    <span id="tds">0.00</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 10px; border-top: 1px solid #666; border-bottom: 1px solid #333; font-weight:bold; background:#333; color:#FFF;"
                                                    colspan="2">
                                                    <a href="#" id="pshipping">
                                                        <i class="fa fa-plus-square"></i>
                                                    </a>
                                                    <span id="tship"></span>
                                                </td>
                                                <td class="text-right"
                                                    style="padding:5px 10px 5px 10px; font-size: 14px;border-top: 1px solid #666; border-bottom: 1px solid #333; font-weight:bold; background:#333; color:#FFF;"
                                                    colspan="2">
                                                    <span id="gtotal">0.00</span>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="clearfix"></div>

                                        <div id="botbuttons" class="col-xs-12 text-center">
                                            <div class="row">

                                                <div class="col-xs-4">
                                                    <div class="row">
                                                        <div class="btn-group-vertical btn-block">
                                                            <input type="hidden" class="item_order_count" value="0" />
                                                            <input type="hidden" class="item_ordered" />


                                                            <a class="btn cl-primary" id="btnMoveRoom"
                                                                href="{{ route('pos.table.addTable', ['move' => 1]) }}">
                                                                <i class="fa fa-retweet" aria-hidden="true"></i> Move
                                                            </a>
                                                            <a href="{{ route('pos.clearTable') }}" id="btnClear"
                                                                class="btn cl-primary"><i class="fa fa-times"
                                                                    aria-hidden="true"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-4">
                                                    <div class="row">
                                                        <div class="btn-group-vertical btn-block">
                                                            <button type="button" class="btn cl-primary btn-block"
                                                                id="print_order">
                                                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Order
                                                            </button>
                                                            <button type="button"
                                                                class="btn cl-primary btn-block print-bill"
                                                                data-id="{{ $suspend->id ?? 0 }}">
                                                                <i class="fa fa-file-text" aria-hidden="true"></i> Bill
                                                            </button>


                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-4">
                                                    <div class="row">
                                                        <button enabled type="button"
                                                            class="btn cl-danger btn-block payment" id="payment"
                                                            style="height:67px;">
                                                            <i class="fa fa-money"
                                                                style="margin-right: 5px;"></i>Payment </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>


                                        <div style="clear:both; height:5px;"></div>
                                        <div id="num">
                                            <div id="icon"></div>
                                        </div>







                                    </div>
                                </div>

                                <div class="clearfix"></div>
                            </div>


                            <div class="col-xs-6">
                        </form>
                        <div id="cpinner">
                            <div id="panel-top">
                                <div id="quick-categories">
                                    <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1">
                                        <div class="row">
                                            <button class="btn cl-primary" title="Previous" type="button"
                                                id="previous_c">
                                                <i class="fa fa-hand-o-left"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-md-10 col-lg-10 col-xs-10">
                                        <div class="row">
                                            <div class="cpcategory">
                                                @forelse($categories as $c)
                                                    <button type="button" data-id="{{ $c->id }}" value="{{ $c->id }}"
                                                        class="animated ccategory btn cl-primary cl-danger category {{ $cat_id == $c->id ? 'active' : '' }}">
                                                        {{ $c->name }}
                                                    </button>
                                                @empty
                                                    <div class="text-muted p-2">No categories found.</div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1">
                                        <div class="row">
                                            <button class="btn cl-primary" title="Next" type="button" id="next_c">
                                                <i class="fa fa-hand-o-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>


                                    <div id="pos-subcategories" style="margin-top:5px;"></div>
                                </div>
                                <div class="clearfix"></div>
                                <div id="product-search" style="padding-top:5px;">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <input type="text" class="form-control sp_code" placeholder="Code" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <input type="text" class="form-control sp_name" placeholder="Name" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <div id="proContainer">
                                <div id="ajaxproducts">
                                    <div id="item-list">

                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div id="panel-bottom" class="btn-group btn-group-justified pos-grid-nav">
                                        <div class="btn-group">
                                            <button style="z-index:10002;" class="btn cl-primary pos-tip"
                                                title="Previous" type="button" id="previous">
                                                <i class="fa fa-hand-o-left"></i>
                                            </button>
                                        </div>
                                        <div class="btn-group">
                                            <button style="z-index:10004;" class="btn cl-primary pos-tip" title="Next"
                                                type="button" id="next">
                                                <i class="fa fa-hand-o-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>


                        </div>
                        <div style="clear:both;"></div>
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
    </div>
    </div>
    <div id="brands-slider">
        <div id="brands-list">
        </div>
    </div>
    <div id="category-slider">
        <button type="button" class="close open-category"><i class="fa fa-2x">&times;</i></button>
        <div id="category-list">

        </div>
    </div>
    <div id="subcategory-slider">
        <button type="button" class="close open-category"><i class="fa fa-2x">&times;</i></button>
        <div id="subcategory-list">

        </div>
    </div>




    <div class="modal fade in " id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                                class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="payModalLabel">Finalize Sale</h4>
                </div>
                <div class="modal-body" id="payment_content">
                    <div class="row">
                        <div class="col-md-10 col-sm-9">

                            <div class="row">


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sldate">Date</label>
                                        <input type="text" name="date" class="form-control input-tip sldate" id="sldate"
                                            required="required" />
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="biller">Branch</label>
                                        <select name="biller" class="form-control" id="posbiller" required>
                                            @foreach($billers as $biller)
                                                dd($billers)
                                                <option value="{{ $biller->id }}" {{ session('selected_biller_id') == $biller->id ? 'selected' : '' }}>
                                                    {{ $biller->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>




                            </div>


                            <div class="clearfir"></div>

                            <div class="payment-cash">

                                <table class="table table-bordered table-condensed table-striped">
                                    <tbody>
                                        <tr>
                                            <th width="50%" height="45" class="text-left bold">Currency</th>
                                            <th class="text-center">USD</th>
                                            <th class="text-center">KHR</th>
                                        </tr>
                                        <tr>
                                            <td width="50%" height="45" class="text-left bold">Total Items</td>
                                            <td class="text-right"><span class="item_count">0</span></td>
                                            <td class="text-right"><span class="item_count">0</span></td>
                                        </tr>

                                        <tr>
                                            <td width="50%" height="45" class="text-left bold">Total Payable</td>
                                            @foreach ($currencies as $currency)
                                                <td class="text-right">
                                                    <span class="total_payable total_payable_{{ $currency->id }}"
                                                        data-rate="{{ $currency->rate }}"
                                                        id="total_payable_{{ $currency->id }}">0</span>
                                                </td>
                                            @endforeach
                                        </tr>

                                        <tr>
                                            <td width="50%" height="45" class="text-left font-bold">Paid Amount</td>
                                            @foreach ($currencies as $currency)
                                                <td class="text-right">
                                                    <input name="camount[{{ $currency->id }}]"
                                                        data-rate="{{ $currency->rate }}" type="text"
                                                        class="form-control camount text-right" />
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td width="50%" height="45" class="text-left bold">Balance</td>
                                            @foreach ($currencies as $currency)
                                                <td class="text-right">
                                                    <span class="balance balance_{{ $currency->id }}"
                                                        data-base_rate="{{ $currency->base_rate }}"
                                                        data-rate="{{ $currency->rate }}"
                                                        id="balance_{{ $currency->id }}">0</span>
                                                </td>
                                            @endforeach
                                        </tr>


                                    </tbody>
                                </table>
                            </div>

                            <div id="payments" class="hidden">
                                <div class="well well-sm well_1">
                                    <div class="payment">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="paid_by_1">Paying by</label>
                                                    <select name="paid_by[]" id="paid_by_1"
                                                        class="form-control paid_by">
                                                        {!! $cashOptionsHtml !!}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="amount_1">Amount</label> <input name="amount[]"
                                                        type="text" id="amount_1"
                                                        class="pa form-control kb-pad1 amount" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group gc_1" style="display: none;">
                                                    <label for="gift_card_no_1">Gift Card No</label> <input
                                                        name="paying_gift_card_no[]" type="text" id="gift_card_no_1"
                                                        class="pa form-control kb-pad gift_card_no" />
                                                    <div id="gc_details_1"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="payment_note">Payment Note</label> <textarea
                                                        name="payment_note[]" id="payment_note_1"
                                                        class="pa form-control kb-text payment_note"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="multi-payment"></div>
                            <button type="button" class="btn cl-primary col-md-12 addButton">
                                <i class="fa fa-plus"></i> Add More Payments </button>

                            <div style="clear:both; height:15px;"></div>

                            <div class="font16">
                                <table class="table table-bordered table-condensed table-striped"
                                    style="margin-bottom: 0;">
                                    <tbody>
                                        <tr>
                                            <td width="25%">Total Items</td>
                                            <td width="25%" class="text-right"><span id="item_count">0.00</span></td>
                                            <td width="25%">Total Payable</td>
                                            <td width="25%" class="text-right"><span id="twt">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td>Total Paying</td>
                                            <td class="text-right"><span id="total_paying">0.00</span></td>
                                            <td>Balance</td>
                                            <td class="text-right"><span id="balance">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td>Change (USD)</td>
                                            <td class="text-right"><span id="change_usd">0.00</span></td>
                                            <td>Change (Riel)</td>
                                            <td class="text-right"><span id="change_riel">0.00</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3 text-center">
                            <span style="font-size: 1.2em; font-weight: bold;">Quick Cash</span>

                            <div class="btn-group btn-group-vertical">
                                <button type="button" class="btn btn-lg cl-danger quick-cash" id="quick-payable">0.00
                                </button>
                                <button type="button" class="btn btn-lg cl-primary quick-cash">1</button><button
                                    type="button" class="btn btn-lg cl-primary quick-cash">5</button><button
                                    type="button" class="btn btn-lg cl-primary quick-cash">10</button><button
                                    type="button" class="btn btn-lg cl-primary quick-cash">20</button><button
                                    type="button" class="btn btn-lg cl-primary quick-cash">50</button><button
                                    type="button" class="btn btn-lg cl-primary quick-cash">100</button> <button
                                    type="button" class="btn btn-lg cl-danger" id="clear-cash-notes">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-block btn-lg cl-primary" id="submit-sale">Submit</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="comboModal" tabindex="-1" role="dialog" aria-labelledby="comboModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:50%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">
                            <i class="fa fa-2x">&times;</i></span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="comboModalLabel"></h4>
                </div>
                <div class="modal-body" style="margin-top:-15px !important;">
                    <label class="table-label">combo products</label>
                    <table id="comboProduct"
                        class="table items table-striped table-bordered table-condensed table-hover sortable_table">
                        <thead>
                            <tr>
                                <th>Product (Code - Name)</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th width="3%">
                                    <a id="add_comboProduct" class="btn btn-sm btn-primary"><i
                                            class="fa fa-plus"></i></a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="editCombo">Submit</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal" id="cmModal" tabindex="-1" role="dialog" aria-labelledby="cmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">
                            <i class="fa fa-2x">&times;</i></span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="cmModalLabel"></h4>
                </div>
                <div class="modal-body" id="pr_popover_content">
                    <div class="form-group">
                        <label for="tags">Tags</label> <select name="tags" class="form-control" id="tags"
                            style="width:100%;">
                            <option value="0">No</option>
                        </select>
                    </div>
                    <script type="text/javascript">
                        $(function () {
                            $("#tags").on("change", function () {
                                var tags = $(this).val();
                                $("#icomment").val(tags);
                            });
                        });
                    </script>
                    <div class="form-group">
                        <label for="icomment">Comment</label> <textarea name="comment" cols="40" rows="10"
                            class="form-control" id="icomment" style="height:80px;"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="iordered">Ordered</label> <select name="ordered" class="form-control" id="iordered"
                            style="width:100%;">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <input type="hidden" id="irow_id" value="" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="editComment">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="prModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                                class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="prModalLabel"></h4>
                </div>
                <div class="modal-body" id="pr_popover_content">
                    <form class="form-horizontal" role="form">
                        <div class="form-group hidden">
                            <label for="return_quantity" class="col-sm-4 control-label">Return Quantity</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control kb-pad" id="return_quantity">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="pquantity" class="col-sm-4 control-label">Quantity</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control kb-pad" id="pquantity">
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="saleman" class="col-sm-4 control-label">Salesman</label>
                            <div class="col-sm-8">
                                <div id="psalemans-div"></div>
                                <input type="hidden" class="form-control" id="psalesman">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="punit" class="col-sm-4 control-label">Product Unit</label>
                            <div class="col-sm-8">
                                <div id="punits-div"></div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="poption" class="col-sm-4 control-label">Product Option</label>
                            <div class="col-sm-8">
                                <div id="poptions-div"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pdiscount" class="col-sm-4 control-label">Product Discount</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control kb-pad" id="pdiscount">
                            </div>
                        </div>


                        <div class="form-group hidden">
                            <label for="pprice" class="col-sm-4 control-label">Unit Price</label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control kb-pad" id="pprice">
                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <tr>
                                <th style="width:25%;">Net Unit Price</th>
                                <th style="width:25%;"><span id="net_price"></span></th>
                                <th style="width:20%; display:none !important">Product Tax</th>
                                <th style="width:20%; display:none !important"><span id="pro_tax"></span></th>
                                <th style="width:25%;">Total</th>
                                <th style="width:25%;"><span id="pro_total"></span></th>
                            </tr>
                        </table>

                        <div class="panel panel-default">
                            <div class="panel-heading">Calculate Product discount</div>
                            <div class="panel-body">

                                <div class="form-group hidden">
                                    <label for="tpdiscount" class="col-sm-4 control-label">Discount</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="tpdiscount">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="hpro_total" />
                        <input type="hidden" id="punit_price" value="" />
                        <input type="hidden" id="old_tax" value="" />
                        <input type="hidden" id="old_qty" value="" />
                        <input type="hidden" id="old_price" value="" />
                        <input type="hidden" id="row_id" value="" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="editItem">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade in" id="gcModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></button>
                    <h4 class="modal-title" id="myModalLabel">Sell Gift Card</h4>
                </div>
                <div class="modal-body">
                    <p>Please fill in the information below. The field labels marked with * are required input fields.
                    </p>

                    <div class="alert alert-danger gcerror-con" style="display: none;">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        <span id="gcerror"></span>
                    </div>
                    <div class="form-group">
                        <label for="gccard_no">Card No</label> *
                        <div class="input-group">
                            <input type="text" name="gccard_no" value="" class="form-control" id="gccard_no" />
                            <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                <a href="#" id="genNo"><i class="fa fa-cogs"></i></a>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="gcname" value="Gift Card" id="gcname" />

                    <div class="form-group">
                        <label for="gcvalue">Value</label> *
                        <input type="text" name="gcvalue" value="" class="form-control" id="gcvalue" />
                    </div>
                    <div class="form-group">
                        <label for="gcprice">Price</label> *
                        <input type="text" name="gcprice" value="" class="form-control" id="gcprice" />
                    </div>
                    <div class="form-group">
                        <label for="gccustomer">Customer</label> <input type="text" name="gccustomer" value=""
                            class="form-control" id="gccustomer" />
                    </div>
                    <div class="form-group">
                        <label for="gcexpiry">Expiry Date</label> <input type="text" name="gcexpiry" value="06/08/2027"
                            class="form-control date" id="gcexpiry" />
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="addGiftCard" class="btn btn-primary">Sell Gift Card</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade in" id="mModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                                class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="mModalLabel">Add Product Manually</h4>
                </div>
                <div class="modal-body" id="pr_popover_content">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="mcode" class="col-sm-4 control-label">Product Code *</label>
                            <div style="width:64%; padding-left:2.55%" class="col-sm-8 input-group">
                                <input type="text" class="form-control" id="mcode">
                                <span class="input-group-addon pointer" id="random_num" style="padding: 1px 10px;">
                                    <i class="fa fa-random"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mname" class="col-sm-4 control-label">Product Name *</label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control kb-text" id="mname">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mquantity" class="col-sm-4 control-label">Quantity *</label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control kb-pad" id="mquantity">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mdiscount" class="col-sm-4 control-label">Product Discount</label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control kb-pad" id="mdiscount">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mprice" class="col-sm-4 control-label">Unit Price *</label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control kb-pad" id="mprice">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mcost" class="col-sm-4 control-label">Unit Cost</label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control kb-pad" id="mcost">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="add_product" class="col-sm-4 control-label">Add to List Products</label>
                            <div class="col-sm-8">
                                <select id="add_product" class="add_product" style="width:100%">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <tr>
                                <th style="width:25%;">Net Unit Price</th>
                                <th style="width:25%;"><span id="mnet_price"></span></th>
                                <th style="width:25%;">Product Tax</th>
                                <th style="width:25%;"><span id="mpro_tax"></span></th>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="addItemManually">Submit</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade in" id="dsModal" tabindex="-1" role="dialog" aria-labelledby="dsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-2x">&times;</i>
                    </button>
                    <h4 class="modal-title" id="dsModalLabel">Edit Order Discount</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="order_discount_input">Order Discount</label> <input type="text"
                            name="order_discount_input" value="" class="form-control kb-pad"
                            id="order_discount_input" />
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="updateOrderDiscount" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade in" id="sModal" tabindex="-1" role="dialog" aria-labelledby="sModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-2x">&times;</i>
                    </button>
                    <h4 class="modal-title" id="sModalLabel">Shipping</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="shipping_input">Shipping</label> <input type="text" name="shipping_input" value=""
                            class="form-control kb-pad" id="shipping_input" />
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="updateShipping" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade in" id="txModal" tabindex="-1" role="dialog" aria-labelledby="txModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></button>
                    <h4 class="modal-title" id="txModalLabel">Edit Order Tax</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="order_tax_input">Order Tax</label><select name="order_tax_input"
                            id="order_tax_input" class="form-control pos-input-tip" style="width:100%;">
                            <option value="" selected="selected"></option>
                            <option value="1">No Tax</option>
                            <option value="2">VAT @10%</option>
                            <option value="3">GST @6%</option>
                            <option value="4">VAT @20%</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="updateOrderTax" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade in" id="susModal" tabindex="-1" role="dialog" aria-labelledby="susModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></button>
                    <h4 class="modal-title" id="susModalLabel">Suspend Sale</h4>
                </div>
                <div class="modal-body">
                    <p>Please type reference note and submit to suspend this sale</p>

                    <div class="form-group">
                        <label for="reference_note">Reference Note</label><input type="text" name="reference_note"
                            value="" class="form-control kb-text" id="reference_note" />
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="suspend_sale" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>


    <div id="order_tbl_drink" data-item="drink" class="hidden print_order">
        <span id="order_span_drink"></span>
        <table id="order-table-drink" class="prT table table-striped" style="margin-bottom:0;" width="100%"></table>
    </div>

    <div id="order_tbl_food" data-item="food" class="hidden print_order">
        <span id="order_span_food"></span>
        <table id="order-table-food" class="prT table table-striped" style="margin-bottom:0;" width="100%"></table>
    </div>

    <div id="order_tbl_cake" data-item="cake" class="hidden print_order">
        <span id="order_span_cake"></span>
        <table id="order-table-cake" class="prT table table-striped" style="margin-bottom:0;" width="100%"></table>
    </div>

    <div id="order_tbl">
        <span id="order_span"></span>
        <table id="order-table" class="prT table table-striped" style="margin-bottom:0;" width="100%"></table>
    </div>

    <span class="hidden" id="bill_company">Wincodetech</span>
    <span class="hidden" id="bill_address">#101(2nd floor), St.598, Phnom Penh Tmey, Sensok<br>Phnom Penh
        <br>Cambodia</span>
    <span class="hidden" id="bill_phone">Tel: +855 69 64 00 00 / +855 99 46 72 72</span>
    <div id="bill_tbl">
        <span id="bill_span"></span>
        <table id="bill-table" width="100%" class="prT table table-striped table-condensed" style="margin-bottom:0;">
        </table>
        <table id="bill-total-table" class="prT table" style="margin-bottom:0;" width="100%"></table>
        <center><span id="bill_number"
                style="font-size:38px; color:#EEE; opacity:0.5; font-weight:bold; top:15%; position:absolute;"></span>
        </center>
        <span id="bill_footer"></span>
    </div>


    <div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true"></div>
    <div class="modal fade in" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2"
        aria-hidden="true"></div>
    <div id="modal-loading" style="display: none;">
        <div class="blackbg"></div>
        <div class="loader"></div>
    </div>


    <!-- Invoice Modal -->
    <div class="modal fade" id="invoice-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body p-0" id="invoice-container"></div>
            </div>
        </div>
    </div>







    <script type="text/javascript" src="{{ asset('assets/js_pos/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js_pos/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js_pos/perfect-scrollbar.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js_pos/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js_pos/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js_pos/custom.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js_pos/jquery.calculator.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js_pos/bootstrapValidator.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/pos/js/plugins.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/pos/js/parse-track-data.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/pos/js/pos.js') }}"></script>



    <script type="text/javascript"
        charset="UTF-8">(function ($) { "use strict"; $.fn.select2.locales['bms'] = { formatMatches: function (matches) { if (matches === 1) { return "One result is available, press enter to select it."; } return matches + "results are available, use up and down arrow keys to navigate."; }, formatNoMatches: function () { return "No matches found"; }, formatInputTooShort: function (input, min) { var n = min - input.length; return "Please type " + n + " or more characters"; }, formatInputTooLong: function (input, max) { var n = input.length - max; if (n == 1) { return "Please delete " + n + " character"; } else { return "Please delete " + n + " characters"; } }, formatSelectionTooBig: function (n) { if (n == 1) { return "You can only select " + n + " item"; } else { return "You can only select " + n + " items"; } }, formatLoadMore: function (pageNumber) { return "Loading more results..."; }, formatSearching: function () { return "Searching..."; }, formatAjaxError: function () { return "Ajax request failed"; }, }; $.extend($.fn.select2.defaults, $.fn.select2.locales['bms']); })(jQuery);</script>
    <div id="ajaxCall"><i class="fa fa-spinner fa-pulse"></i></div>
    <style type="text/css">
        #pos #product-list {
            position: absolute;
            overflow: hidden;
            width: 100%;
            height: 410px;
            border-bottom: 1px solid #DDD;
        }

        #add_item_suggestions {
            margin-top: 3.5%;
        }

        .suggest-list {
            position: absolute;
            z-index: 1100;
            width: 100%;
            max-height: 320px;
            overflow-y: auto;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        }

        .suggest-item {
            display: flex;
            gap: 8px;
            padding: 8px;
            align-items: center;
            cursor: pointer;
            border-bottom: 1px solid #f1f1f1;
        }

        .suggest-item:hover,
        .suggest-item.active {
            background: #f3f4f6;
        }

        .suggest-item .code {
            font-weight: 600;
            margin-right: 6px;
        }

        .suggest-item .meta {
            color: #666;
            font-size: 13px;
        }

        .suggest-empty {
            padding: 10px;
            color: #777;
            text-align: center;
        }

        #add_item {
            position: relative;
        }

        @media print {
            .modal {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                z-index: 999999 !important;
                background: none !important;
            }

            .modal-dialog,
            .modal-content {
                display: block !important;
                visibility: visible !important;
                transform: none !important;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }

            /* show only invoice area (if you want) */
            body * {
                visibility: hidden !important;
            }

            #invoice-modal,
            #invoice-modal * {
                visibility: visible !important;
            }
        }
    </style>




    <script type="text/javascript">


        $(document).ready(function () {
            $('cpcategory').click(function () {
                per_page_c -= category_row;
                if (per_page_c < 0) {
                    per_page_c = 0;
                }
                $.ajax({
                    type: "get",
                    data: { per_page: per_page_c, active: cat_id },
                    dataType: "json",
                    success: function (data) {
                        $('.cpcategory').empty();
                        var newPrs = $('<div></div>');
                        newPrs.html(data.html);
                        newPrs.appendTo(".cpcategory");
                        cpointer();
                    }
                }).done(function () {
                    $('#modal-loading').hide();
                });

            });


        });






        var warehousesLoaded = false;
        function loadItems() {
            var raw = null;
            try { raw = localStorage.getItem('positems'); } catch (e) { console.warn('Could not read positems', e); }
            try {
                positems = raw ? JSON.parse(raw) : {};
            } catch (e) {
                console.error('Error parsing positems:', e);
                positems = {};
            }

            function fmtMoney(v) {
                if (typeof formatMoney === 'function') return formatMoney(v);
                return Number(v || 0).toFixed(2);
            }
            function fmtInt(v) {
                return parseInt(v || 0, 10) || 0;
            }

            var $tbody = $('#posTable tbody');
            if ($tbody.length === 0) {
                console.warn('#posTable tbody not found');
                return;
            }
            $tbody.empty();

            var total = 0;
            var total_qty = 0;
            var distinct_count = 0;

            var keys = Object.keys(positems || {});
            if (keys.length === 0) {
                $tbody.append('<tr class="no-items"><td colspan="5" class="text-center" style="padding:20px;">No items added</td></tr>');
            } else {
                keys.forEach(function (k) {
                    var itemObj = positems[k];
                    if (!itemObj || !itemObj.row) return;
                    var r = itemObj.row;
                    var units = Array.isArray(r.units) ? r.units : [];
                    var currentUnitId = r.current_unit_id || r.unit_id || (units[0] ? units[0].unit_id : null);
                    var currentUnit = units.find(u => String(u.unit_id) === String(currentUnitId)) || null;

                    // Prefer row.price (saved from modal) when present; otherwise fall back to unit price
                    var price = (typeof r.price !== 'undefined' && r.price !== null && r.price !== '') ?
                        (parseFloat(r.price) || 0) :
                        (currentUnit ? parseFloat(currentUnit.price || 0) : (parseFloat(r.price || 0) || 0));

                    // ensure numeric storage in row
                    r.price = Number(price);
                    r.current_unit_id = currentUnitId;

                    var qty = parseFloat(r.qty || 0) || 0;
                    if (qty <= 0) qty = 1;
                    var subtotal = Number(r.subtotal !== undefined ? r.subtotal : (r.price * qty));

                    total += Number(subtotal) || 0;
                    total_qty += qty;
                    distinct_count++;

                    var code = r.code || '';
                    var name = r.name || '';

                    var unitSelect = '';
                    if (units.length) {
                        unitSelect = '<select class="form-control pos-unit" data-id="' + escapeAttr(k) + '" style="width:120px; margin:0 auto;">';
                        units.forEach(function (u) {
                            var uid = escapeAttr(u.unit_id);
                            var uname = escapeHtml(u.name || ('Unit ' + uid));
                            // show option data-price from unit price (units might also have been updated on save if you prefer that approach)
                            var uprice = (typeof u.price !== 'undefined') ? parseFloat(u.price).toFixed(2) : fmtMoney(price);
                            var sel = String(u.unit_id) === String(currentUnitId) ? ' selected' : '';
                            unitSelect += '<option value="' + uid + '" data-price="' + uprice + '"' + sel + '>' + uname + '</option>';
                        });
                        unitSelect += '</select>';
                    } else {
                        unitSelect = '<div class="no-units" style="color:#999;">No units</div>';
                    }

                    var iconsHtml = '<span class="pos-comment" style="margin-left:6px;color:#777;"><i class="fa fa-comment-o"></i></span>';
                    iconsHtml += ' <span class="pos-edit" style="margin-left:6px;color:#777;"><i class="fa fa-edit"></i></span>';

                    var productText = (code ? (escapeHtml(code) + ' - ') : '') + escapeHtml(name);
                    var row =
                        '<tr data-id="' + escapeAttr(k) + '">' +
                        '<td style="vertical-align: middle;">' +
                        '<div style="font-weight:600;">' + productText + '</div>' +
                        '</td>' +
                        '<td style="vertical-align: middle;">' +
                        '<div style="color:#999; margin-top:4px;">' + unitSelect + ' <span style="margin-left:8px;">' + iconsHtml + '</span></div>' +
                        '</td>' +
                        '<td class="text-right" style="vertical-align: middle;">' + fmtMoney(r.price) + ' <small style="color:#999;">(0.00)</small></td>' +
                        '<td class="text-center" style="vertical-align: middle; width:130px;">' +
                        '<input type="text" class="form-control pos-qty" data-id="' + escapeAttr(k) + '" value="' + fmtInt(qty) + '" style="width:80px; margin: 0 auto;" />' +
                        '</td>' +
                        '<td class="text-right line-subtotal" style="vertical-align: middle;">' + fmtMoney(subtotal) + '</td>' +
                        '<td class="text-center" style="vertical-align: middle; width:40px;">' +
                        '<button type="button" class="btn btn-link btn-remove-item" data-id="' + escapeAttr(k) + '" title="Remove"><i class="fa fa-times" style="font-size:18px;color:#000;"></i></button>' +
                        '</td>' +
                        '</tr>';

                    $tbody.append(row);
                });
            }


            // Update counters and totals in DOM
            $('#titems').text(distinct_count);
            $('#item_count, .item_count').text(total_qty);
            $('#twt').text(fmtMoney(total));
            $('#total').text(fmtMoney(total));
            $('#gtotal').text(fmtMoney(total));
            $('#total_items').val(distinct_count);

            var orderTaxPercent = parseFloat($('#postax2').val() || 0);
            var orderDiscount = parseFloat($('#posdiscount').val() || 0) || 0;
            var shipping = parseFloat($('#posshipping').val() || 0) || 0;

            var orderTaxAmount = 0;
            if (orderTaxPercent > 0 && orderTaxPercent <= 100) {
                orderTaxAmount = total * orderTaxPercent / 100;
            } else {
                orderTaxAmount = 0;
            }

            $('#ttax2').text(fmtMoney(orderTaxAmount));
            $('#tds').text(fmtMoney(orderDiscount));

            var grand = total - orderDiscount + shipping;
            $('#gtotal').text(fmtMoney(grand));

            $('#twt').text(fmtMoney(grand));

            try { localStorage.setItem('positems', JSON.stringify(positems)); } catch (e) { console.warn('Could not save positems', e); }

            // Handlers
            $('#posTable').off('click', '.btn-remove-item').on('click', '.btn-remove-item', function () {
                var id = $(this).data('id').toString();
                if (!id) return;
                delete positems[id];
                try { localStorage.setItem('positems', JSON.stringify(positems)); } catch (e) { }
                loadItems();
            });

            $('#posTable').off('change', '.pos-qty').on('change', '.pos-qty', function () {
                var id = $(this).data('id').toString();
                var newQty = parseInt($(this).val(), 10) || 0;
                if (newQty <= 0) {
                    newQty = 1;
                    $(this).val(newQty);
                }
                if (positems && positems[id]) {
                    positems[id].row.qty = newQty;
                    // recalc subtotal based on currently selected unit price (row.price)
                    var curPrice = parseFloat(positems[id].row.price || 0) || 0;
                    positems[id].row.subtotal = Number(curPrice * newQty);
                    try { localStorage.setItem('positems', JSON.stringify(positems)); } catch (e) { }
                    if (typeof calculateTotals === 'function') calculateTotals();
                    loadItems();
                }
            });

            // NEW: when unit changes, update price and subtotal
            $('#posTable').off('change', '.pos-unit').on('change', '.pos-unit', function () {
                var id = $(this).data('id').toString();
                var $opt = $(this).find('option:selected');
                var newUnitId = $opt.val();
                var newPrice = parseFloat($opt.data('price') || 0) || 0;

                if (positems && positems[id]) {
                    positems[id].row.current_unit_id = newUnitId;
                    // if row.price exists (saved from modal) we want to allow the unit's price to override only if row.price is empty/not set
                    // Here we set the row.price to the selected option price (user selected a different unit)
                    positems[id].row.price = Number(newPrice);
                    var qty = parseFloat(positems[id].row.qty || 0) || 1;
                    positems[id].row.subtotal = Number(newPrice * qty);
                    try { localStorage.setItem('positems', JSON.stringify(positems)); } catch (e) { }
                    if (typeof calculateTotals === 'function') calculateTotals();
                    loadItems();
                }
            });

            // helper escapes
            function escapeHtml(text) {
                if (text === null || text === undefined) return '';
                return String(text)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }
            function escapeAttr(text) {
                return (text === null || text === undefined) ? '' : String(text).replace(/"/g, '&quot;').replace(/'/g, '&#039;');
            }

            widthFunctions();


            if (!warehousesLoaded) {
                $.ajax({
                    url: "{{ route('pos.getWarehouses') }}",
                    method: "GET",
                    dataType: "json",
                    success: function (data) {
                        var $select = $('#poswarehouse');
                        $select.empty();
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(function (w) {
                                $select.append('<option value="' + w.id + '">' + w.name + '</option>');
                            });
                        } else {
                            $select.append('<option value="">No warehouses found</option>');
                        }
                        warehousesLoaded = true; // mark loaded
                    },
                    error: function () {
                        console.error('Failed to load warehouses');
                    }
                });
            }


            var $cust = $('#poscustomer');

            $cust.select2({
                placeholder: 'Select Customer',
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('pos.searchCustomer') }}",
                    dataType: 'json',
                    quietMillis: 200,
                    data: function (term, page) {
                        return { q: term };
                    },
                    results: function (data, page) {
                        const arr = (data && data.data) ? data.data : data;
                        return {
                            results: (Array.isArray(arr) ? arr : []).map(function (c) {
                                return {
                                    id: String(c.id || 1),
                                    text: (c.name || '') + (c.phone ? ' (' + c.phone + ')' : '')
                                };
                            })
                        };
                    }
                },
                initSelection: function (element, callback) {
                    // Set default selection on first load
                    callback({
                        id: '1',   // Default ID (General Customer)
                        text: 'General Customer'
                    });
                }
            });




            $cust.select2('data', { id: '1', text: 'General Customer' });

            (function setDefaultCustomer() {
                const defaultCustomer = { id: '1', text: 'General Customer' };
                if ($cust.prop('tagName').toLowerCase() !== 'select') {

                    return;
                }


                if ($cust.find("option[value='" + defaultCustomer.id + "']").length === 0) {
                    const option = new Option(defaultCustomer.text, defaultCustomer.id, true, true);
                    $cust.append(option);
                } else {
                    $cust.val(defaultCustomer.id);
                }


                $cust.trigger('change.select2');
            })();

        }





    </script>




    <script>
        (function ($) {
            const input = $('#add_item');
            if (!input.length) return;

            // Change this to your real search endpoint if different
            const searchUrl = "{{ route('pos.searchProductByName') }}"; // replace with route('pos.searchProducts') if you prefer blade route()
            const getProductUrl = "{{ route('pos.getProductDataByCode') }}"; // your existing endpoint

            // debounce helper
            function debounce(fn, wait) { let t; return function (...args) { clearTimeout(t); t = setTimeout(() => fn.apply(this, args), wait); }; }

            // create suggestions container right after the input
            let $box = $('#add_item_suggestions');
            if (!$box.length) {
                $box = $('<div id="add_item_suggestions" class="suggest-list" style="display:none;"></div>');
                // position it absolutely below input using position/offset
                input.after($box);
                // ensure container width matches input
                function positionBox() {
                    const off = input.position(); // uses relative container; using .offset() may be needed depending on layout
                    const w = input.outerWidth();
                    $box.css({ width: w + 'px' });
                }
                $(window).on('resize', positionBox);
                positionBox();
            }

            let suggestions = []; // current suggestion list
            let selIndex = -1;

            function renderSuggestions(list) {
                suggestions = Array.isArray(list) ? list : [];
                selIndex = -1;
                if (!suggestions.length) {
                    $box.html('<div class="suggest-empty">No products found</div>').show();
                    return;
                }
                const html = suggestions.map((p, i) => {
                    // p should have id, code, name, price, maybe image
                    const code = p.code ? `<span class="code">${escapeHtml(p.code)}</span>` : '';
                    const name = escapeHtml(p.name || p.title || p.product_name || '');
                    const price = (p.price !== undefined && p.price !== null) ? `<div class="meta">${Number(p.price).toFixed(2)}</div>` : '';
                    return `<div class="suggest-item" data-idx="${i}" data-id="${escapeAttr(p.id)}" tabindex="-1">
                
                <div style="flex:1 1 auto;">
                  <div>${code} <span>${name}</span></div>
                  ${price}
                </div>
              </div>`;
                }).join('');
                $box.html(html).show();
            }

            function hideSuggestions() { $box.hide(); selIndex = -1; suggestions = []; }

            // utility escapes
            function escapeHtml(s) { if (s === null || s === undefined) return ''; return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;'); }
            function escapeAttr(s) { if (s === null || s === undefined) return ''; return String(s).replace(/"/g, '&quot;').replace(/'/g, '&#39;'); }

            // ask backend for suggestions
            const fetchSuggestions = debounce(function (query) {
                if (!query || !query.trim()) {
                    // optionally hide or show recent items; currently hide when empty
                    renderSuggestions([]);
                    return;
                }
                $.ajax({
                    url: searchUrl,
                    method: 'GET',
                    dataType: 'json',
                    data: { q: query },
                    success: function (res) {
                        // adapt to server response shape:
                        // Accept either: plain array res = [{id,code,name,price,...}, ...]
                        // or envelope { success:true, data: [...] } or { results: [...] }
                        let list = [];
                        if (Array.isArray(res)) list = res;
                        else if (res && Array.isArray(res.data)) list = res.data;
                        else if (res && Array.isArray(res.results)) list = res.results;
                        else if (res && Array.isArray(res.items)) list = res.items;

                        // If user typed an exact barcode/code that matches a returned product,
                        // immediately add it and do NOT show the suggestion list.
                        // `query` is available because fetchSuggestions receives it.
                        if (query && list && list.length) {
                            const qLower = String(query).trim().toLowerCase();
                            const exact = list.find(it => {
                                // try common fields where barcode/code may be stored
                                return String(it.code || it.barcode || it.sku || it.id || '').toLowerCase() === qLower;
                            });
                            if (exact) {
                                // add it directly (will fetch full product details in chooseSuggestionByObject)
                                chooseSuggestionByObject(exact);
                                return; // don't call renderSuggestions
                            }
                        }

                        renderSuggestions(list);
                    },
                    error: function (xhr) {
                        console.error('search error', xhr.responseText || xhr.statusText);
                        renderSuggestions([]);
                    }
                });
            }, 160);

            // when user types
            input.on('input', function (e) {
                const q = $(this).val();
                fetchSuggestions(q);
            });

            // show suggestions on focus / click
            input.on('focus click', function (e) {
                const q = $(this).val();
                if (q && q.trim()) fetchSuggestions(q);
                else {
                    // optionally fetch popular/recent products if you want:
                    // $.get(searchUrl, { recent:1 }, ... )
                    $box.html('<div class="suggest-empty">Type product name or code to search</div>').show();
                }
            });

            // keyboard navigation
            input.on('keydown', function (e) {
                if ($box.is(':hidden')) return;
                const items = $box.find('.suggest-item');
                if (!items.length) return;
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    selIndex = Math.min(selIndex + 1, items.length - 1);
                    highlight();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    selIndex = Math.max(selIndex - 1, 0);
                    highlight();
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (selIndex >= 0 && selIndex < suggestions.length) {
                        chooseSuggestion(selIndex);
                    } else {
                        // if no suggestion selected, try exact code match — we will call search endpoint to find exact match by code
                        const q = $(this).val().trim();
                        if (q) {
                            // attempt to find suggestion by exact code from currently loaded suggestions
                            const exact = suggestions.find(it => String(it.code || '').toLowerCase() === q.toLowerCase());
                            if (exact) {
                                chooseSuggestion(suggestions.indexOf(exact));
                                return;
                            }
                            // fallback: call search with exact full string and pick the first result
                            $.get(searchUrl, { q: q }).done(function (res) {
                                let list;
                                if (Array.isArray(res)) list = res;
                                else list = res && res.data ? res.data : (res.results || []);
                                if (list && list.length) {
                                    chooseSuggestionByObject(list[0]);
                                } else {
                                    // maybe user typed a product id/code — call getProductDataByCode with id/code
                                    // We first try as id, then as code if server supports
                                    $.get(getProductUrl, { id: q }).done(function (productData) {
                                        if (productData) add_invoice_item(productData);
                                        else bootbox && bootbox.alert ? bootbox.alert('No product found') : alert('No product found');
                                    }).fail(function () { bootbox && bootbox.alert ? bootbox.alert('No product found') : alert('No product found'); });
                                }
                            });
                        }
                    }
                } else if (e.key === 'Escape') {
                    hideSuggestions();
                }
            });

            function highlight() {
                $box.find('.suggest-item').removeClass('active');
                if (selIndex >= 0) {
                    const $el = $box.find('.suggest-item').eq(selIndex);
                    $el.addClass('active');
                    // scroll into view if needed
                    const elTop = $el.position().top;
                    const elBottom = elTop + $el.outerHeight();
                    const boxScrollTop = $box.scrollTop();
                    const boxHeight = $box.innerHeight();
                    if (elBottom > boxScrollTop + boxHeight) $box.scrollTop(elBottom - boxHeight);
                    if (elTop < boxScrollTop) $box.scrollTop(elTop);
                }
            }

            // choose suggestion by index
            function chooseSuggestion(idx) {
                const item = suggestions[idx];
                if (!item) return;
                chooseSuggestionByObject(item);
            }

            function chooseSuggestionByObject(item) {
                // if object already contains full product data (e.g., price, units), we can pass directly
                // but safer: call getProductDataByCode endpoint (by id) to get the canonical object
                if (item && item.id) {
                    $.ajax({
                        url: getProductUrl,
                        method: 'GET',
                        dataType: 'json',
                        data: { id: item.id }
                    }).done(function (product) {
                        if (!product) {
                            bootbox && bootbox.alert ? bootbox.alert('Product not found') : alert('Product not found');
                            return;
                        }
                        add_invoice_item(product);
                        // clear input and hide dropdown
                        input.val('');
                        hideSuggestions();
                    }).fail(function () {
                        // fallback: if item is already full product
                        if (item.name || item.code) {
                            add_invoice_item(item);
                            input.val('');
                            hideSuggestions();
                        } else {
                            bootbox && bootbox.alert ? bootbox.alert('Failed to fetch product details') : alert('Failed to fetch product details');
                        }
                    });
                } else {
                    // if no id but object might be full product
                    add_invoice_item(item);
                    input.val('');
                    hideSuggestions();
                }
            }

            // click on suggestion
            $box.on('click', '.suggest-item', function (e) {
                const idx = parseInt($(this).attr('data-idx'), 10);
                if (!isNaN(idx)) chooseSuggestion(idx);
            });

            // click outside hides suggestions
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#add_item, #add_item_suggestions').length) {
                    hideSuggestions();
                }
            });

            // small helper to pre-load some products on first focus (optional)
            // input.one('focus', function(){ $.get(searchUrl, { recent:1 }).done(res=> renderSuggestions(Array.isArray(res)?res:res.data||[])); });

        })(jQuery);
    </script>



    <script>
        $(function () {
            var ocat_id = "13";
            var per_page_c = 0;
            var category_row = parseInt(15, 10) || 15;
            var selectedCats = [];
            var routeUrl = "{{ route('pos.ajaxCategoryData') }}";

            function showLoading(show) { if (show) $('#modal-loading').show(); else $('#modal-loading').hide(); }
            function formatCategoryParam(ids) { if (!ids) return []; return Array.isArray(ids) ? ids : [ids]; }

            function loadCategory(categoryIds, perPage) {
                perPage = perPage || 0;

                categoryIds = formatCategoryParam(categoryIds);

                var ajaxData = { per_page: perPage };
                if (Array.isArray(categoryIds) && categoryIds.length > 0) {
                    ajaxData.category_id = categoryIds;
                }

                showLoading(true);
                $.ajax({
                    type: 'GET',
                    url: routeUrl,
                    data: ajaxData,
                    dataType: 'json'
                }).done(function (res) {
                    showLoading(false);

                    if (res.subcategories !== undefined) $('#pos-subcategories').html(res.subcategories);
                    else $('#pos-subcategories').empty();

                    if (res.products !== undefined) {
                        $('#item-list').html(res.products);
                        try { $('#item-list').perfectScrollbar('update'); } catch (e) { }
                    } else {
                        $('#item-list').empty();
                    }

                    // update active classes (support data-id and value)
                    $('.ccategory, .category').removeClass('active');
                    selectedCats.forEach(function (id) {
                        $('.ccategory[data-id="' + id + '"], .category[data-id="' + id + '"]').addClass('active');
                        $('.ccategory[value="' + id + '"], .category[value="' + id + '"]').addClass('active');
                    });
                }).fail(function (xhr, status, err) {
                    showLoading(false);
                    console.error('loadCategory error', status, err);
                    if (typeof bootbox !== 'undefined') bootbox.alert('Failed to load category data.');
                    else alert('Failed to load category data.');
                });
            }

            var selectedCats = selectedCats || [];
            var per_page_c = typeof per_page_c !== 'undefined' ? per_page_c : 0;
            var category_row = typeof category_row !== 'undefined' ? category_row : 12;
            var currentIndex = 0;

            // helper: return array of category ids from DOM in order
            function getCategoryIds() {
                // find unique category elements in DOM order
                var ids = [];
                $('.category, .ccategory').each(function () {
                    var id = parseInt($(this).data('id') || $(this).val() || 0, 10);
                    if (id && ids.indexOf(id) === -1) ids.push(id);
                });
                return ids;
            }


            function loadCategoryByIndex(idx) {
                var ids = getCategoryIds();
                if (!ids.length) return;
                if (idx < 0) idx = 0;
                if (idx >= ids.length) idx = ids.length - 1;
                currentIndex = idx;

                // set selectedCats to this single id and reset pagination offset
                selectedCats = [ids[currentIndex]];
                per_page_c = 0;

                // visually mark active (optional)
                $('.category, .ccategory').removeClass('active');
                $('.category[data-id="' + selectedCats[0] + '"], .ccategory[data-id="' + selectedCats[0] + '"]').addClass('active');

                // call your existing loader
                loadCategory(selectedCats, per_page_c);


            }

            // clicking a category button: select that category and set currentIndex
            $(document).on('click', '.ccategory, .category', function (e) {
                e.preventDefault();
                var cid = parseInt($(this).data('id') || $(this).val() || 0, 10);
                if (!cid) return;

                var ids = getCategoryIds();
                var idx = ids.indexOf(cid);
                if (idx === -1) {
                    // if clicked category not found in list, default behaviour
                    selectedCats = [cid];
                    per_page_c = 0;
                    loadCategory(selectedCats, per_page_c);
                    return;
                }

                // If clicking an already selected category, clear -> show all (preserve your original UX)
                var already = (selectedCats.length && selectedCats[0] === cid);
                if (already) {
                    selectedCats = [];
                    per_page_c = 0;
                    loadCategory(selectedCats, per_page_c);

                    return;
                }

                loadCategoryByIndex(idx);
            });



            // initial load: optionally preselect a category from a hidden field
            $(document).ready(function () {
                var ids = getCategoryIds();
                var initial = parseInt($('#cat_id').val() || 0, 10);
                if (initial && initial > 0 && ids.indexOf(initial) !== -1) {
                    currentIndex = ids.indexOf(initial);
                } else {
                    currentIndex = 0;
                }
                loadCategoryByIndex(currentIndex);
            });


            $(document).on('change keypress keyup', '.sp_code,.sp_name', function () {
                var cat_id = getCategoryIds();
                if (cat_id != $(this).val()) {
                    var sp_code = $(".sp_code").val().trim();
                    var sp_name = $(".sp_name").val().trim();
                    $.ajax({
                        type: "get",
                        url: "{{ route('pos.ajaxCategoryData') }}",
                        data: {
                            category_id: cat_id,
                            sp_code: sp_code,
                            sp_name: sp_name,
                        },
                        dataType: "json",
                        success: function (data) {
                            $('#item-list').empty();
                            // create DOM from HTML string then append its children directly
                            var $frag = $('<div></div>').html(data.products || '');
                            $('#item-list').append($frag.children());

                            $('#subcategory-list').empty().append($('<div></div>').html(data.subcategories || '').children());
                            tcp = data.tcp;
                            try { $('#item-list').perfectScrollbar('update'); } catch (e) { }
                        }
                    }).done(function () {
                        p_page = 'n';
                        $('#category-' + cat_id).addClass('active');
                        $('#category-' + ocat_id).removeClass('active');
                        ocat_id = cat_id;
                        $('#modal-loading').hide();
                        // nav_pointer();
                    });
                }
            });


            $(function () {



                /** -------------------------------
                 * Payment modal open
                 * ------------------------------- */
                $('#payment').off('click.safePayment').on('click.safePayment', function (e) {
                    e.preventDefault();

                    const dateFormat = (typeof site !== 'undefined' && site.dateFormats && site.dateFormats.js_ldate)
                        ? site.dateFormats.js_ldate + ':ss'
                        : 'MM/DD/YYYY HH:mm:ss';
                    safeDateInit($('#sldate, #podate'), dateFormat);

                    const totalFromDom = parseNumberSafe($('#gtotal').text()) || parseNumberSafe($('#twt').text());
                    const totalQty = parseNumberSafe($('#item_count').text()) || parseNumberSafe($('.item_count').text());
                    const distinctCount = parseNumberSafe($('#titems').text()) || parseNumberSafe($('#total_items').val());

                    if (totalQty === 0 || distinctCount === 0) {
                        alert('Please add product before payment.');
                        return false;
                    }

                    const showTotal = safeFormatDecimal(totalFromDom, 2);
                    $('#twt').text(showTotal);
                    $('#quick-payable').text(showTotal);
                    $('#item_count, .item_count').text(totalQty);

                    $('#paymentModal').appendTo('body').modal({
                        backdrop: 'static',
                        keyboard: false,
                        show: true
                    });

                    $('#paymentModal').off('shown.bs.modal.focusAmount').on('shown.bs.modal.focusAmount', function () {
                        setTimeout(() => {
                            const $amount = $('#amount_1');
                            if ($amount.val() === '' || parseNumberSafe($amount.val()) === 0) {
                                $amount.val(showTotal);
                            }
                            $amount.focus();
                            recalcPaymentUI();
                        }, 10);
                    });
                });

                /** -------------------------------
                * Central update for payment fields
                * ------------------------------- */
                function updatePaymentAmounts(newAmountFormatted, rate = 1) {
                    // Update corresponding input
                    $(`input[name="camount[${rate}]"]`).val(newAmountFormatted).trigger('keyup');
                    $('.base_amount').val(newAmountFormatted).trigger('keyup');
                    recalcPaymentUI();
                }

                /** -------------------------------
                 * Quick Cash buttons
                 * ------------------------------- */
                $(document).off('click.quickCash').on('click.quickCash', '.quick-cash', function (ev) {
                    ev.preventDefault();
                    const $btn = $(this);
                    const btnText = $btn.clone().children().remove().end().text().trim();
                    const addValue = parseNumberSafe(btnText);

                    const $amountInput = $('#amount_1');
                    const currentAmount = parseNumberSafe($amountInput.val());
                    let newAmount = 0;

                    if ($btn.is('#quick-payable')) {
                        // SET total (not add)
                        newAmount = addValue;
                    } else {
                        // ADD to current
                        newAmount = currentAmount + addValue;
                    }

                    const formatted = safeFormatDecimal(newAmount, 2);
                    updatePaymentAmounts(formatted);

                    const $badge = $btn.find('span.badge');
                    if ($badge.length === 0) $btn.append('<span class="badge">1</span>');
                    else $badge.text(parseInt($badge.text(), 10) + 1);

                    $amountInput.focus();
                });

                /** -------------------------------
                 * Clear Cash Notes
                 * ------------------------------- */
                $(document).off('click.clearCashNotes').on('click.clearCashNotes', '#clear-cash-notes', function (ev) {
                    ev.preventDefault();
                    $('.quick-cash').find('.badge').remove();
                    $('input[name^="camount"]').val('0.00').trigger('keyup');
                    $('#amount_1').val('0.00').focus();
                });

                /** -------------------------------
                 * Recalculate Total Paying, Balance, Change
                 * ------------------------------- */
                window.recalcPaymentUI = function () {
                    let totalPayingUSD = 0;
                    let totalPayingKHR = 0;

                    // Get KHR conversion rate from DOM (fallback to 4100)
                    const khrRate = parseNumberSafe($('.total_payable[data-rate!="1"]').data('rate')) || 4100;

                    // Loop through all currency inputs
                    $('input[name^="camount"]').each(function () {
                        const $inp = $(this);
                        const rate = parseNumberSafe($inp.data('rate')) || 1;
                        const paid = parseNumberSafe($inp.val()) || 0;


                        const payable = parseNumberSafe($(`.total_payable[data-rate="${rate}"]`).text()) || 0;



                        const totalFromDom = parseNumberSafe($('#gtotal').text()) || parseNumberSafe($('#twt').text());

                        const bal = totalFromDom - paid;

                        if (rate === 1) {
                            // USD row: show USD then KHR equivalent
                            const balUSD = totalFromDom;
                            const balKHR = balUSD * khrRate;
                            $(`.total_payable[data-rate="${rate}"]`).text(`${formatCurrencyUSD(balUSD)}`);

                        } else {
                            // KHR row: show KHR then USD equivalent
                            const KHR = totalFromDom * khrRate;
                            const balKHR = KHR;
                            $(`.total_payable[data-rate="${rate}"]`).text(`${formatCurrencyKHR(balKHR)}`);

                        }


                        // Format both USD and KHR for display (Option B)
                        if (rate === 1) {
                            // USD row: show USD then KHR equivalent
                            const balUSD = bal;
                            const balKHR = balUSD * khrRate;
                            $(`.balance[data-rate="${rate}"]`).text(`${formatCurrencyUSD(balUSD)}`);
                            totalPayingUSD += paid;
                        } else {
                            // KHR row: show KHR then USD equivalent
                            const KHR = totalFromDom * khrRate;
                            const balKHR = KHR - paid;

                            $(`.balance[data-rate="${rate}"]`).text(`${formatCurrencyKHR(balKHR)}`);
                            totalPayingKHR += paid;
                        }
                    });

                    // Total payable
                    const totalPayableUSD = parseNumberSafe($('.total_payable[data-rate="1"]').text()) || 0;
                    const totalPayableKHR = parseNumberSafe($('.total_payable[data-rate!="1"]').text()) || 0;

                    const totalBalanceUSD = totalPayableUSD - totalPayingUSD;
                    const totalBalanceKHR = totalPayableKHR - totalPayingKHR;

                    // Change calculation

                    const changeUSD = Math.max(totalPayingUSD - totalPayableUSD, 0);
                    const changeKHR = changeUSD * khrRate;

                    const totalFromDom = parseNumberSafe($('#gtotal').text()) || parseNumberSafe($('#twt').text());

                    const totalbl = totalFromDom - totalPayingUSD - totalPayingKHR / khrRate;

                    // Update UI
                    $('#total_paying').text(safeFormatDecimal(totalPayingUSD + totalPayingKHR / khrRate, 2));

                    $('#balance').text(safeFormatDecimal(totalbl, 2));

                    $('#change_usd').text(safeFormatDecimal(changeUSD, 2));
                    $('#change_riel').text(safeFormatDecimal(changeKHR, 2));
                };

                /** -------------------------------
                 * Helper functions
                 * ------------------------------- */



                // nicer currency formatters (adds thousands separators + decimals)
                function formatCurrencyUSD(val) {
                    const n = Number(val || 0);
                    // show leading $ and two decimals
                    return '$' + n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }

                function formatCurrencyKHR(val) {
                    const n = Number(val || 0);
                    // show Khmer Riel symbol and two decimals (you can drop decimals if you prefer)
                    return '៛ ' + n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }

                /** -------------------------------
                 * Auto-trigger recalc on input changes
                 * ------------------------------- */
                $(document).on('keyup change', 'input[name^="camount"], #amount_1, .base_amount', function () {
                    recalcPaymentUI();
                });

                $('#paymentModal').on('shown.bs.modal', function () {
                    recalcPaymentUI();
                });

                /** -------------------------------
                 * Helper functions
                 * ------------------------------- */
                function parseNumberSafe(val) {
                    val = String(val).replace(/,/g, '');
                    return isNaN(val) ? 0 : parseFloat(val);
                }

                function safeFormatDecimal(val, decimals = 2) {
                    return parseFloat(val).toFixed(decimals);
                }

            });




            // safe, global helpers
            window.escapeHtml = function (text) {
                if (text === null || text === undefined) return '';
                return String(text)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            };

            window.escapeAttr = function (text) {
                if (text === null || text === undefined) return '';
                return String(text).replace(/"/g, '&quot;').replace(/'/g, '&#039;');
            };


            // Attach handler after loadItems() builds the table (put this near the end of loadItems or in document ready)
            $('#posTable').off('click', '.pos-edit').on('click', '.pos-edit', function (e) {
                e.preventDefault();
                var id = $(this).closest('tr').data('id') || $(this).data('id');
                if (!id) return;

                id = String(id);
                var item = (typeof positems !== 'undefined' && positems[id]) ? positems[id].row : null;
                if (!item) return console.warn('Item not found for id', id);

                // Basic fields
                $('#prModalLabel').text((item.code ? (item.code + ' - ') : '') + (item.name || ''));
                $('#pquantity').val(item.qty || 1);


                if (item.salesman_name) {
                    $('#psalemans-div').html('<div>' + escapeHtml(item.salesman_name) + '</div>');
                    $('#psalesman').val(item.salesman_id || '');
                } else {
                    $('#psalemans-div').html('<div class="text-muted">n/a</div>');
                    $('#psalesman').val('');
                }

                // Units: build select inside #punits-div (similar to unitSelect in loadItems)
                var units = Array.isArray(item.units) ? item.units : (Array.isArray(item.row_units) ? item.row_units : []);
                if (units.length) {
                    var html = '<select id="punit" class="form-control">';
                    var curUnitId = item.current_unit_id || item.unit_id || (units[0] ? units[0].unit_id : '');
                    units.forEach(function (u) {
                        var uprice = (typeof u.price !== 'undefined') ? parseFloat(u.price).toFixed(2) : (parseFloat(item.price || 0).toFixed(2));
                        var sel = String(u.unit_id) === String(curUnitId) ? ' selected' : '';
                        html += '<option value="' + escapeAttr(u.unit_id) + '" data-price="' + uprice + '"' + sel + '>' + escapeHtml(u.name || u.unit_name || ('Unit ' + u.unit_id)) + '</option>';
                    });
                    html += '</select>';
                    $('#punits-div').html(html);
                } else {
                    $('#punits-div').html('<div class="text-muted">No units</div>');
                }

                // Price/total displays
                var price = parseFloat(item.price || 0) || 0;
                var qty = parseFloat(item.qty || 1) || 1;
                // if unit select exists, use selected option price
                var selectedUnitPrice = $('#punits-div').find('option:selected').data('price');
                if (selectedUnitPrice !== undefined) price = parseFloat(selectedUnitPrice) || price;

                var netPrice = Number(price).toFixed(2);
                var total = (price * qty).toFixed(2);

                $('#pprice').val(netPrice);          // hidden or visible field depending on your form
                $('#punit_price').val(netPrice);     // your hidden input
                $('#net_price').text(netPrice);
                $('#pro_total').text(total);
                $('#hpro_total').val(total);
                $('#old_qty').val(item.qty || 1);
                $('#old_price').val(item.price || netPrice);
                $('#row_id').val(id);

                // If product tax exists on item
                if (typeof item.tax !== 'undefined') {
                    $('#pro_tax').text(Number(item.tax).toFixed(2));
                    $('#old_tax').val(item.tax);
                } else {
                    $('#pro_tax').text('');
                    $('#old_tax').val('');
                }

                // Show modal (Bootstrap)
                $('#prModal').modal('show');
            });





            // Helper: parse discount string (e.g. "10%" => percent, "5" => absolute)
            function parseDiscountInput(discountStr, unitPrice) {
                discountStr = (discountStr || '').toString().trim();
                if (!discountStr) return { type: 'none', value: 0, amount: 0, priceAfter: unitPrice };
                var percentMatch = discountStr.match(/^([0-9]+(?:\.[0-9]+)?)\s*%$/);
                if (percentMatch) {
                    var pct = parseFloat(percentMatch[1]) || 0;
                    if (pct < 0) pct = 0;
                    if (pct > 100) pct = 100;
                    var amt = unitPrice * pct / 100;
                    return { type: 'percent', value: pct, amount: amt, priceAfter: Number(unitPrice - amt) };
                }
                // otherwise treat as absolute amount
                var num = parseFloat(discountStr.replace(/[^0-9.\-]/g, '')) || 0;
                if (num < 0) num = 0;
                if (num > unitPrice) num = unitPrice;
                return { type: 'amount', value: num, amount: num, priceAfter: Number(unitPrice - num) };
            }

            // Replace/update the modal totals logic to include discount calculation
            function updateModalTotalsWithDiscount() {
                var $unit = $('#punit');
                var price = 0;
                if ($unit.length && $unit.find('option:selected').length) {
                    price = parseFloat($unit.find('option:selected').data('price') || 0) || 0;
                } else {
                    price = parseFloat($('#pprice').val() || 0) || 0;
                }
                var qty = parseFloat($('#pquantity').val() || 0) || 1;
                if (qty <= 0) { qty = 1; $('#pquantity').val(qty); }

                // read discount input (allow "10%" or "5")
                var discountInput = $('#pdiscount').val() || '';
                var d = parseDiscountInput(discountInput, price);

                var netPerUnit = Number(d.priceAfter).toFixed(2);
                var lineTotal = Number(d.priceAfter * qty).toFixed(2);
                var discountAmount = Number(d.amount).toFixed(2);

                // display
                $('#net_price').text(netPerUnit);        // net unit price AFTER discount
                $('#pro_total').text(lineTotal);         // total after discount
                // optionally show product discount in some place
                // store hidden values for submit
                $('#punit_price').val(Number(price).toFixed(2)); // original price
                $('#pprice').val(netPerUnit);                    // net shown in hidden input
                $('#hpro_total').val(lineTotal);
                $('#tpdiscount').val(discountInput);             // if you use this hidden field
                // a hidden numeric discount amount for other uses
                $('#discount_amount_hidden').remove(); // remove old if present
                $('<input>').attr({ type: 'hidden', id: 'discount_amount_hidden', value: discountAmount }).appendTo('#pr_popover_content');
            }

            // wire up handlers (quantity, unit change, discount change)
            $('#prModal').off('change', '#punit').on('change', '#punit', updateModalTotalsWithDiscount);
            $('#prModal').off('input change', '#pquantity').on('input change', '#pquantity', function () { updateModalTotalsWithDiscount(); });
            $('#prModal').off('input change', '#pdiscount').on('input change', '#pdiscount', function () { updateModalTotalsWithDiscount(); });

            // ensure totals update when modal shows
            $('#prModal').on('show.bs.modal', function () { setTimeout(updateModalTotalsWithDiscount, 8); });

            // Submit: save discount and new subtotal to positems and reload
            $('#prModal').off('click', '#editItem').on('click', '#editItem', function (e) {
                e.preventDefault();
                var id = String($('#row_id').val() || '');
                if (!id) return console.warn('no row_id set');

                if (typeof positems === 'undefined' || !positems[id]) {
                    console.warn('positems missing for id', id);
                    $('#prModal').modal('hide');
                    return;
                }

                var qty = parseInt($('#pquantity').val(), 10) || 1;
                if (qty <= 0) qty = 1;
                var unitId = ($('#punit').length) ? String($('#punit').val()) : ($('#punit').attr('value') || '');
                var originalPrice = parseFloat($('#punit').find('option:selected').data('price') || $('#punit_price').val() || 0) || 0;
                var discountInput = ($('#pdiscount').val() || '').toString().trim();
                var parsed = parseDiscountInput(discountInput, originalPrice);

                // update structure
                positems[id].row.qty = qty;
                positems[id].row.current_unit_id = unitId || positems[id].row.current_unit_id;
                positems[id].row.price = Number(parsed.priceAfter).toFixed(2); // store net unit price as price used in calculation
                positems[id].row.original_price = Number(originalPrice).toFixed(2); // keep original price
                positems[id].row.product_discount = discountInput;   // raw string (e.g., "10%" or "5")
                positems[id].row.discount_amount = Number(parsed.amount).toFixed(2); // numeric discount per unit
                positems[id].row.subtotal = Number(parsed.priceAfter * qty).toFixed(2); // discounted subtotal

                // persist and refresh UI
                try { localStorage.setItem('positems', JSON.stringify(positems)); } catch (err) { console.warn('Could not save positems', err); }
                if (typeof loadItems === 'function') loadItems();

                $('#prModal').modal('hide');
            });

            // parse order discount string relative to a base amount (total)
            function parseOrderDiscountInput(inputStr, baseAmount) {
                inputStr = (inputStr || '').toString().trim();
                if (!inputStr) return 0;
                var percentMatch = inputStr.match(/^([0-9]+(?:\.[0-9]+)?)\s*%$/);
                if (percentMatch) {
                    var pct = parseFloat(percentMatch[1]) || 0;
                    if (pct < 0) pct = 0;
                    if (pct > 100) pct = 100;
                    return (baseAmount * pct / 100);
                }
                // otherwise absolute number
                var val = parseFloat(inputStr.replace(/[^0-9.\-]/g, '')) || 0;
                if (val < 0) val = 0;
                return val;
            }

            // show modal when clicking the small edit icon
            $(document).off('click', '#ppdiscount').on('click', '#ppdiscount', function (e) {
                e.preventDefault();
                // get current order discount (numeric) shown in the form
                var cur = parseFloat($('#posdiscount').val() || 0) || 0;
                // display as-is (user can change to "10%" etc)
                $('#order_discount_input').val(Number(cur).toFixed(2));
                $('#dsModal').modal('show');
                setTimeout(function () { $('#order_discount_input').focus().select(); }, 50);
            });

            // update handler: compute discount and refresh totals
            $(document).off('click', '#updateOrderDiscount').on('click', '#updateOrderDiscount', function (e) {
                e.preventDefault();
                // read raw user input
                var raw = $('#order_discount_input').val() || '';

                // need the current subtotal/total before order-level discount:
                // try to get the subtotal displayed in #twt or compute from positems
                var subtotal = 0;
                // prefer reading existing numeric total if present
                var twtVal = parseFloat($('#twt').text().replace(/,/g, '') || 0);
                if (!isNaN(twtVal) && twtVal > 0) {
                    // note: #twt may include grand total; best to compute raw subtotal from positems
                    // but we'll attempt to compute a base subtotal by summing item subtotals stored in positems
                    subtotal = 0;
                    try {
                        if (typeof positems !== 'undefined') {
                            Object.keys(positems || {}).forEach(function (k) {
                                var r = positems[k] && positems[k].row;
                                if (r) {
                                    var s = parseFloat(typeof r.subtotal !== 'undefined' ? r.subtotal : (r.price * r.qty)) || 0;
                                    subtotal += s;
                                }
                            });
                        }
                    } catch (err) {
                        subtotal = twtVal;
                    }
                    // fallback
                    if (subtotal === 0) subtotal = twtVal;
                } else {
                    // fallback compute
                    subtotal = 0;
                    try {
                        if (typeof positems !== 'undefined') {
                            Object.keys(positems || {}).forEach(function (k) {
                                var r = positems[k] && positems[k].row;
                                if (r) {
                                    var s = parseFloat(typeof r.subtotal !== 'undefined' ? r.subtotal : (r.price * r.qty)) || 0;
                                    subtotal += s;
                                }
                            });
                        }
                    } catch (err) {
                        subtotal = 0;
                    }
                }

                // compute discount amount
                var discountAmount = parseOrderDiscountInput(raw, subtotal);
                discountAmount = Number(discountAmount) || 0;

                // set the form input that loadItems() reads (posdiscount)
                $('#posdiscount').val(Number(discountAmount).toFixed(2));
                // optional: show a formatted value elsewhere
                $('#tds').text(Number(discountAmount).toFixed(2));

                // persist posdiscount to localStorage if you want (optional)
                try {
                    var settings = localStorage.getItem('pos_settings') ? JSON.parse(localStorage.getItem('pos_settings')) : {};
                    settings.order_discount = Number(discountAmount).toFixed(2);
                    localStorage.setItem('pos_settings', JSON.stringify(settings));
                } catch (e) { /* ignore */ }

                // refresh totals: call your existing functions
                if (typeof calculateTotals === 'function') calculateTotals();
                if (typeof loadItems === 'function') loadItems();

                // close modal
                $('#dsModal').modal('hide');
            });



        });


        $(function () {


            // compute starting index
            var existingPayments = $('#multi-payment').children('.payment-block').length;
            var pa = existingPayments > 0 ? existingPayments + 2 : 2;

            function ensureCamount(idx, rate) {
                var name = 'camount[' + idx + ']';
                var $c = $('input[name="' + name + '"]');
                if ($c.length === 0) {
                    $c = $('<input/>', {
                        type: 'hidden',
                        name: name,
                        class: 'camount',
                        'data-rate': rate || 1,
                        value: '0.00'
                    }).appendTo('#paymentModal form, form').first();
                } else {
                    $c.attr('data-rate', rate || 1);
                }
                return $c;
            }

            function removeCamount(idx) {
                $('input[name="camount[' + idx + ']"]').remove();
            }



            // payment index counter
            var pa = 1; // next payment index

            // Add new payment block
            $(document).on('click', '.addButton', function () {
                if (pa > 5) {
                    bootbox.alert('Max allowed limit reached.');
                    return false;
                }

                try { $('#paid_by_1, #pcc_type_1').select2('destroy'); } catch (e) { }

                // Clone the template
                var phtml = $('#payments').html();
                var update_html = phtml.replace(/_1/g, '_' + pa);

                // Wrap in temporary div to manipulate selects
                var $tmp = $('<div>').html(update_html);

                // Clone options from first select.paid_by
                $tmp.find('select.paid_by').each(function () {
                    $(this).html($('#paid_by_1').html());
                    // force first option selected
                    $(this).val($(this).find('option:first').val());
                });

                update_html = $tmp.html();

                // Append to multi-payment container
                $('#multi-payment').append(
                    '<div class="payment-block" style="position:relative;margin-bottom:8px;">' +
                    '<button type="button" class="close close-payment" style="position:absolute;right:8px;top:0;">' +
                    '<i class="fa fa-1x" style="font-weight:bold;">&times;</i></button>' +
                    update_html +
                    '</div>'
                );

                // Initialize select2
                try {
                    $('#paid_by_1, #pcc_type_1, #paid_by_' + pa + ', #pcc_type_' + pa).select2({ minimumResultsForSearch: 7 });
                } catch (e) { }

                // Set data-rate for amount input (cash always USD = 1)
                var $newAmount = $('#amount_' + pa);
                $newAmount.attr('data-rate', 2);
                ensureCamount(pa, 2);
                syncAmountToCamount($newAmount);

                if (typeof read_card === 'function') read_card();

                pa++;
                $('#paymentModal').css('overflow-y', 'scroll');
            });

            // Remove payment block
            $(document).on('click', '.close-payment', function () {
                $(this).closest('.payment-block').remove();
                if (pa > 1) pa--;
            });

            // Sync amount to camount input
            function syncAmountToCamount($amountInput) {
                var id = $amountInput.attr('id') || '';
                var m = id.match(/_(\d+)$/);
                var idx = m ? m[1] : null;
                var rate = parseFloat($amountInput.attr('data-rate')) || 1;
                var val = $amountInput.val() || '0.00';

                if (idx) {
                    var $c = ensureCamount(idx, rate);
                    $c.val(val).attr('data-rate', rate).trigger('change');
                } else {
                    var $cNear = $amountInput.closest('.payment').find('input.camount').first();
                    if ($cNear.length) {
                        $cNear.val(val).attr('data-rate', rate).trigger('change');
                    }
                }
            }

            // Handle amount input changes
            $(document).on('keyup change', 'input.amount', function () {
                var $this = $(this);
                var $sel = $this.closest('.payment').find('select.paid_by').first();
                if ($sel.length) {
                    var selOption = $sel.find('option:selected');
                    // Cash = USD always
                    var r = (selOption.attr('cash_type') === 'cash') ? 1 : parseFloat(selOption.data('rate')) || 1;
                    $this.attr('data-rate', r);
                } else {
                    $this.attr('data-rate', 1);
                }
                syncAmountToCamount($this);
                if (typeof recalcPaymentUI === 'function') recalcPaymentUI();
            });



            /* When user changes paid_by — determine rate without relying on cash account having rate */
            $(document).on('change', 'select.paid_by', function () {
                var $sel = $(this);
                var selId = $sel.attr('id') || '';
                var match = selId.match(/_(\d+)$/);
                var idx = match ? match[1] : null;

                var selOpt = $sel.find('option:selected');
                var optRate = parseFloat(selOpt.data('rate'));
                var rate;
                if (optRate && !isNaN(optRate)) {
                    rate = optRate;
                } else {
                    // no data-rate on option: decide by cash_type (cash => USD=1), else fallback to 1
                    var ctype = selOpt.attr('cash_type') || 'cash';
                    rate = (ctype === 'cash') ? 1 : 1;
                }

                if (idx) {
                    var $amount = $('#amount_' + idx);
                    if ($amount.length) {
                        $amount.attr('data-rate', rate);
                        var $c = ensureCamount(idx, rate);
                        $c.attr('data-rate', rate);
                        syncAmountToCamount($amount);
                    }
                } else {
                    var $amountNearby = $sel.closest('.payment').find('input.amount').first();
                    if ($amountNearby.length) {
                        $amountNearby.attr('data-rate', rate);
                        var m = ($amountNearby.attr('id') || '').match(/_(\d+)$/);
                        var idxNearby = m ? m[1] : null;
                        if (idxNearby) ensureCamount(idxNearby, rate);
                        syncAmountToCamount($amountNearby);
                    }
                }

                if (typeof recalcPaymentUI === 'function') recalcPaymentUI();
            });

            /* Mirror visible amounts into hidden camounts for recalc */
            $(document).on('keyup change', 'input.amount', function () {
                var $this = $(this);
                if (typeof $this.attr('data-rate') === 'undefined' || $this.attr('data-rate') === '') {
                    var $sel = $this.closest('.payment').find('select.paid_by').first();
                    if ($sel.length) {
                        var r = parseFloat($sel.find('option:selected').data('rate')) || (($sel.find('option:selected').attr('cash_type') === 'cash') ? 1 : 1);
                        $this.attr('data-rate', r);
                    } else {
                        $this.attr('data-rate', 1);
                    }
                }
                syncAmountToCamount($this);
                if (typeof recalcPaymentUI === 'function') recalcPaymentUI();
            });


            /* On modal show ensure camount[1] exists */
            $('#paymentModal').on('shown.bs.modal', function () {
                var $amount1 = $('#amount_1');
                if ($amount1.length) {
                    var r = parseFloat($amount1.attr('data-rate')) || parseFloat($('#paid_by_1 option:selected').data('rate')) || 1;
                    $amount1.attr('data-rate', r);
                    ensureCamount(1, r);
                    syncAmountToCamount($amount1);
                }
                if (typeof recalcPaymentUI === 'function') recalcPaymentUI();
            });

        });

        (function () {
            // ensure a hidden input exists to store the chosen order tax (server may already have one)
            if ($('#order_tax').length === 0) {
                $('<input>').attr({ type: 'hidden', id: 'order_tax', name: 'order_tax', value: '' }).appendTo('body');
            }

            // helper to get option label text for a given value
            function getOrderTaxLabel(val) {
                var $opt = $('#order_tax_input').find('option[value="' + (val || '') + '"]');
                return $opt.length ? $opt.text().trim() : '';
            }

            // clicking the edit icon: open modal and preselect current value
            $(document).on('click', '#pptax2', function (e) {
                e.preventDefault();
                var cur = $('#order_tax').val() || '';
                $('#order_tax_input').val(cur);         // select raw value
                // If select2 is used on #order_tax_input, trigger change to update UI
                if (typeof $ !== 'undefined' && $('#order_tax_input').data('select2')) {
                    $('#order_tax_input').trigger('change.select2');
                }
                $('#txModal').appendTo('body').modal('show');
            });

            // when user clicks Update in modal
            $(document).on('click', '#updateOrderTax', function (e) {
                e.preventDefault();

                var chosen = $('#order_tax_input').val() || '';
                $('#order_tax').val(chosen);

                // update any visible label (create if not present)
                var labelText = getOrderTaxLabel(chosen) || '—';
                if ($('#order_tax_label').length === 0) {
                    // create a small inline label next to the edit icon (adjust insertion as needed)
                    $('#pptax2').after(' <span id="order_tax_label" style="margin-left:6px;font-weight:600;">' + escapeHtml(labelText) + '</span>');
                } else {
                    $('#order_tax_label').text(labelText);
                }

                // persist to localStorage (optional - helps keep setting when user refreshes)
                try {
                    var settings = localStorage.getItem('pos_settings') ? JSON.parse(localStorage.getItem('pos_settings')) : {};
                    settings.order_tax = chosen;
                    localStorage.setItem('pos_settings', JSON.stringify(settings));
                } catch (err) { /* ignore storage errors */ }

                // refresh totals/UI (only call if functions exist)
                if (typeof calculateTotals === 'function') calculateTotals();
                if (typeof loadItems === 'function') loadItems();

                // close modal
                $('#txModal').modal('hide');
            });

            $('#clearLS').click(function (event) {
                bootbox.confirm("Are you sure?", function (result) {
                    if (result == true) {
                        localStorage.clear();
                        location.reload();
                    }
                });
                return false;
            });


            $(document).on('click', '#btnMoveRoom', function (e) {
                try {
                    localStorage.removeItem('positems');
                } catch (err) {
                    console.warn('Error clearing positems before move:', err);
                }
                // allow link to navigate normally
            });

            $(document).on('click', '#btnClear', function (e) {
                try {
                    localStorage.removeItem('positems');
                } catch (err) {
                    console.warn('Error clearing positems before move:', err);
                }
                // allow link to navigate normally
            });







            // Example: when a room is selected in UI, call this with the id + name
            function setSelectedRoom(roomId, roomName) {
                // write to hidden input
                $('#posroom').val(roomId);

                // update anchor text (optional)
                $('#pos-selected-room-anchor').text(roomName);

                // store to localStorage so it's persistent across refreshes
                localStorage.setItem('posroom', roomId);
                localStorage.setItem('posroom_name', roomName);
            }

            // If you use clickable room elements (example)
            $(document).on('click', '.room-item', function (e) {
                e.preventDefault();
                var rid = $(this).data('room-id') || $(this).attr('data-room-id');
                var rname = $(this).data('room-name') || $(this).text();
                setSelectedRoom(rid, rname);
            });

            $(document).off('click', '#print_order').on('click', '#print_order', function () {



                var raw = localStorage.getItem('positems');
                var positems = raw ? JSON.parse(raw) : {};
                var keys = Object.keys(positems || {});
                if (!keys.length) {
                    bootbox.alert('No items to save');
                    return;
                }

                var roomId = $('#posroom').val()
                    || $('#pos-selected-room-anchor').data('room-id')
                    || localStorage.getItem('posroom')
                    || '';

                roomId = parseInt(roomId, 10); // convert to integer

                if (!roomId || isNaN(roomId)) {
                    bootbox.alert('Please select a valid room before placing the order.');
                    return; // stop execution
                }

                var cid = $('#poscustomer').val();
                if (!cid) {
                    // try select2 data
                    var sdata = $('#poscustomer').select2 ? $('#poscustomer').select2('data') : null;
                    cid = (sdata && sdata.id) ? sdata.id : cid;
                }

                var wid = $('#poswarehouse').val() || $('#poswarehouse option:selected').val() || localStorage.getItem('poswarehouse') || '';

                // then build payload
                var payload = {
                    customer_id: String(cid || '1'),    // default to '1' so it's never null
                    warehouse_id: String(wid || ''),   // blank if none
                    salesman_id: $('#posbiller').val() || '',
                    room_id: roomId,
                    total: parseFloat($('#gtotal').text() || 0) || 0,
                    discount: parseFloat($('#posdiscount').val() || 0) || 0,
                    shipping: parseFloat($('#posshipping').val() || 0) || 0,
                    tax: parseFloat($('#postax2').val() || 0) || 0,
                    items: []
                };

                keys.forEach(function (k) {
                    var r = positems[k] && positems[k].row;
                    if (!r) return;

                    var candidate = r.product_id || r.item_id || r.itemId || r.id || '';
                    var numericId = null;

                    if (typeof candidate === 'string' && candidate.indexOf('_') !== -1) {
                        var parts = candidate.split('_');
                        if (parts.length) numericId = parseInt(parts[0], 10);
                    } else {
                        numericId = parseInt(candidate, 10);
                    }

                    if ((!numericId || isNaN(numericId)) && k) {
                        var kParts = String(k).split('_');
                        numericId = parseInt(kParts[0], 10);
                    }

                    if (!numericId || isNaN(numericId)) {
                        console.error('Invalid product_id for item key', k, 'row:', r);
                        alert('Failed to save order: invalid product id for one or more items. See console for details.');
                        throw new Error('Invalid product_id for item key ' + k);
                    }

                    var item = {
                        product_id: numericId,
                        unit_id: r.current_unit_id || r.unit_id || '',
                        price: Number(r.price || 0),
                        qty: Number(r.qty || 1),
                        subtotal: Number(typeof r.subtotal !== 'undefined' ? r.subtotal : ((Number(r.price || 0) * Number(r.qty || 1)))),
                        name: r.name || '',
                        code: r.code || ''
                    };

                    payload.items.push(item);
                });




                $.ajax({
                    url: '{{ route("pos.saveSuspend") }}',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(payload),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || ''
                    },
                    success: function (res) {
                        if (res && res.success) {
                            // keep existing localStorage positems so user can add more
                            // optionally update with server-canonical items if returned:
                            if (res.items && Array.isArray(res.items)) {
                                // map server items into the same keyed structure (optional)
                                var restored = {};
                                res.items.forEach(function (it, i) {
                                    var key = String(it.product_id) + '_' + i;
                                    restored[key] = { row: it };
                                });
                                try { localStorage.setItem('positems', JSON.stringify(restored)); } catch (e) { console.warn(e); }
                            }
                            loadItems(); // refresh UI
                            if (res.redirect) window.location.href = res.redirect;
                        } else {
                            alert('Failed to save order: ' + (res && res.message ? res.message : 'Unknown error'));
                        }
                    },

                    error: function (xhr) {
                        console.error('AJAX error:', xhr);
                        alert('Error saving order. Check console/network for details.');
                    }
                });
            });



            $(document).on('click', '.print-bill', function () {


                var id = $(this).data('id');
                if (!id) { bootbox.alert('Order id missing.'); return; }
                window.location = 'pos/modal_bill/' + id;
            });



            $(document).on('click', '.suspend-card', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var $card = $(this);
                var url = $card.attr('href');
                if (!url) return console.warn('suspend-card has no href');

                var origOpacity = $card.css('opacity');
                $card.css('opacity', 0.6);

                fetch(url, { method: 'GET', credentials: 'same-origin' })
                    .then(function (resp) {
                        var ct = resp.headers.get('content-type') || '';
                        if (ct.indexOf('application/json') !== -1) return resp.json();
                        return resp.text().then(function (t) {
                            try { return JSON.parse(t); }
                            catch (err) { throw new Error('Invalid JSON response'); }
                        });
                    })
                    .then(function (res) {
                        if (!res || !Array.isArray(res.items)) {
                            console.error('Unexpected suspended sale shape', res);
                            alert('Failed to load suspended sale (invalid response). See console.');
                            $card.css('opacity', origOpacity);
                            return;
                        }

                        // --- POS ITEMS ---
                        var positems = {};
                        res.items.forEach(function (it, idx) {
                            var key = String(it.id ?? it.product_id ?? ('s' + idx + Math.random().toString(36).slice(2)));
                            var currentUnitId = it.unit_id ?? null;
                            var price = parseFloat(it.price || 0) || 0;
                            var qty = parseFloat(it.qty || it.quantity || 1) || 1;
                            var subtotal = parseFloat(it.subtotal || (price * qty)) || (price * qty);
                            var units = Array.isArray(it.units) ? it.units : [];

                            var unitOptions = '';
                            units.forEach(function (u) {
                                var uid = (u.unit_id !== undefined) ? String(u.unit_id) : (u.product_unit_id ? String(u.product_unit_id) : '');
                                var label = u.name || u.unit_name || ('Unit ' + uid);
                                var val = uid;
                                var selected = (currentUnitId !== null && String(currentUnitId) === val) ? ' selected' : '';
                                var dataQty = (u.qty !== undefined) ? u.qty : 1;
                                var dataPrice = (u.price !== undefined && u.price !== null) ? u.price : 0;
                                unitOptions += '<option value="' + escapeHtml(val) + '"' + selected + ' data-qty="' + escapeHtml(String(dataQty)) + '" data-price="' + escapeHtml(String(dataPrice)) + '">' + escapeHtml(label) + '</option>';
                            });

                            var unitSelectHtml = '<select class="form-control pos-unit" data-id="' + escapeHtml(key) + '" style="width:120px; margin:0 auto;">' + unitOptions + '</select>';

                            positems[key] = {
                                row: {
                                    id: it.id ?? null,
                                    product_id: it.product_id ?? null,
                                    code: it.code ?? '',
                                    name: it.name ?? '',
                                    qty: qty,
                                    price: Number(price),
                                    subtotal: Number(subtotal),
                                    units: units,
                                    current_unit_id: currentUnitId,
                                    unit_select_html: unitSelectHtml
                                }
                            };
                        });

                        try { localStorage.setItem('positems', JSON.stringify(positems)); }
                        catch (err) { console.error('Could not save positems', err); alert('Could not save items locally.'); $card.css('opacity', origOpacity); return; }
                        // CUSTOMER
                        if (res.customer_id !== undefined && res.customer_id !== null) {
                            var cid = String(res.customer_id);
                            var cname = res.customer_name || 'General Customer';
                            var $cust = $('#poscustomer');

                            if ($cust.length) {
                                if ($cust.hasClass('select2-hidden-accessible')) {
                                    // Select2 field
                                    if ($cust.find("option[value='" + cid + "']").length === 0) {
                                        // add new option if it doesn't exist
                                        var newOption = new Option(cname, cid, true, true);
                                        $cust.append(newOption).trigger('change');
                                    } else {
                                        $cust.val(cid).trigger('change');
                                    }
                                } else {
                                    // plain input
                                    $cust.val(cname).attr('data-customer-id', cid).trigger('input').trigger('change');
                                }
                            }

                            // Fallback hidden inputs
                            if ($('#customer').length) $('#customer').val(cid).trigger('change');
                            if ($('#cust_id').length) $('#cust_id').val(cid).trigger('change');
                            if ($('[name="customer_id"]').length) $('[name="customer_id"]').val(cid).trigger('change');

                            if ($('#poscustomer_text').length) $('#poscustomer_text').text(cname);
                            if ($('#customer_name').length) $('#customer_name').text(cname);
                        }


                        // --- WAREHOUSE ---
                        if (res.warehouse_id !== undefined && res.warehouse_id !== null) {
                            var wid = String(res.warehouse_id);
                            var wname = res.warehouse_name || 'Warehouse ' + wid;
                            var $wh = $('#poswarehouse');

                            if ($wh.length && $wh.hasClass('select2-hidden-accessible')) {
                                if ($wh.find("option[value='" + wid + "']").length === 0) {
                                    var newWhOption = new Option(wname, wid, true, true);
                                    $wh.append(newWhOption).trigger('change');
                                } else {
                                    $wh.val(wid).trigger('change');
                                }
                            } else if ($wh.length) {
                                if ($wh.find("option[value='" + wid + "']").length === 0) $wh.append('<option value="' + wid + '">' + wname + '</option>');
                                $wh.val(wid).trigger('change');
                            }

                            if ($('#poswarehouse_text').length) $('#poswarehouse_text').text(wname);
                            if ($('#warehouse_name').length) $('#warehouse_name').text(wname);
                        }

                        // --- ROOM ---
                        (function handleRoom() {
                            var rid = res.room_id ?? (res.room?.id ?? null);
                            var rname = res.room_name ?? res.room?.name ?? res.room?.number ?? String(rid ?? '');

                            // Set hidden inputs
                            if (rid !== null) {
                                if ($('#posroom').length) $('#posroom').val(rid).trigger('change');
                                if ($('#room_id').length) $('#room_id').val(rid).trigger('change');
                                if ($('[name="room_id"]').length) $('[name="room_id"]').val(rid).trigger('change');
                            }

                            // Update visible anchor
                            var $displayAnchor = $('a.pos-tip.cl-danger').first();
                            if ($displayAnchor.length) {
                                $displayAnchor.html(rname ? escapeHtml(rname) : '<i class="fa fa-toggle-off"></i>');
                                var baseHref = ($displayAnchor.attr('href') || '').split('?')[0];
                                $displayAnchor.attr('href', baseHref + '?room_id=' + encodeURIComponent(rid));
                            }

                            // Update text placeholders
                            if ($('#room_text').length) $('#room_text').text(rname);
                            if ($('#posroom_text').length) $('#posroom_text').text(rname);

                            // --- STORE ROOM IN LOCALSTORAGE ---
                            if (rid !== null) {
                                localStorage.setItem('posroom', JSON.stringify({ id: rid, name: rname }));
                            }
                        })();


                        // --- BRANCH ---
                        if (res.branch_id !== undefined && res.branch_id !== null) {
                            var bid = String(res.branch_id);
                            if ($('#branch').length) $('#branch').val(bid).trigger('change');
                            if ($('[name="branch_id"]').length) $('[name="branch_id"]').val(bid).trigger('change');
                        }

                        // --- SERVED BY ---
                        if (res.served_by !== undefined && res.served_by !== null) {
                            if ($('#served_by').length) $('#served_by').val(String(res.served_by)).trigger('change');
                            if ($('#served_by_text').length) $('#served_by_text').text(res.served_by_name || res.served_by);
                        }

                        if (typeof loadItems === 'function') loadItems();
                        $('.modal').modal('hide');
                        $card.css('opacity', origOpacity);

                    })
                    .catch(function (err) {
                        console.error('Error loading suspended sale:', err);
                        alert('Failed to load suspended sale. Check console/network.');
                        $card.css('opacity', origOpacity);
                    });

                return false;
            });






            $(document).off('click', '#submit-sale').on('click', '#submit-sale', function (e) {
                e.preventDefault();

                // load positems
                var raw = null;
                try { raw = localStorage.getItem('positems'); } catch (e) { raw = null; }
                var positems = raw ? JSON.parse(raw) : {};
                var keys = Object.keys(positems || {});
                if (!keys.length) {
                    bootbox && bootbox.alert ? bootbox.alert('No items to save') : alert('No items to save');
                    return;
                }

                // basic fields
                var roomId = $('#posroom').val() || localStorage.getItem('posroom') || '';
                var cid = $('#poscustomer').val() || '1';
                var wid = $('#poswarehouse').val() || '';
                var biller = $('#posbiller').val() || '';

                // numeric totals read from DOM
                var total = parseFloat($('#total').text() || $('#twt').text() || 0) || 0;
                // var tax = parseFloat($('#ttax2').text() || 0) || 0;
                var returned = parseFloat($('#returned_amount').text() || 0) || 0; // add element if needed
                var discount = parseFloat($('#posdiscount').val() || 0) || 0;
                var shipping = parseFloat($('#posshipping').val() || 0) || 0;
                var grandTotal = parseFloat($('#gtotal').text() || 0) || 0;

                var paid = parseFloat($('#total_paying').text() || 0) || 0;
                var balance = parseFloat($('#balance').text() || 0) || 0;
                var returnAmount = parseFloat($('#change_usd').text() || 0) || 0;
                var punit = parseFloat($('#punit').text() || 0) || 0;

                var payload = {

                    customer_id: String(cid || '1'),
                    warehouse_id: String(wid || ''),
                    salesman_id: String(biller || ''),
                    room_id: roomId || '',

                    total: total,
                    // tax: tax,
                    returned: returned,
                    discount: discount,
                    shipping: shipping,
                    grand_total: grandTotal,

                    paid: paid,
                    balance: balance,
                    return_amount: returnAmount,

                    delivery_status: $('#delivery_status_select').val() || 'pending',
                    payment_status: $('#payment_status_select').val() || (paid >= grandTotal ? 'paid' : (paid > 0 ? 'partial' : 'pending')),
                    note: $('#note').val() || '',

                    items: []
                };

                try {
                    keys.forEach(function (k) {
                        var r = positems[k] && positems[k].row;
                        if (!r) return;
                        // determine numeric product id
                        var candidate = r.product_id || r.item_id || r.id || '';
                        var numericId = null;
                        if (typeof candidate === 'string' && candidate.indexOf('_') !== -1) {
                            var parts = candidate.split('_');
                            numericId = parseInt(parts[0], 10);
                        } else {
                            numericId = parseInt(candidate, 10);
                        }
                        if (!numericId || isNaN(numericId)) {
                            var kParts = String(k).split('_');
                            numericId = parseInt(kParts[0], 10);
                        }
                        if (!numericId || isNaN(numericId)) {
                            throw new Error('Invalid product id for item ' + k);
                        }

                        var qty = Number(r.qty || 1) || 1;
                        if (qty <= 0) qty = 1;
                        var unitPrice = Number(r.price || 0) || 0; // price saved in row is the unit price selected
                        var subtotal = Number(typeof r.subtotal !== 'undefined' ? r.subtotal : (unitPrice * qty)) || (unitPrice * qty);

                        payload.items.push({
                            product_id: numericId,
                            unit_id: r.current_unit_id || r.unit_id || null,
                            unit_price: unitPrice,
                            qty: qty,
                            subtotal: subtotal,
                            name: r.name || '',
                            code: r.code || ''
                        });
                    });
                } catch (err) {
                    console.error('Failed to build payload', err);
                    bootbox && bootbox.alert ? bootbox.alert('Failed to prepare sale data. See console.') : alert('Failed to prepare sale data.');
                    return;
                }

                $('#modal-loading').show();

                $.ajax({
                    url: '{{ route("pos.submitSale") }}',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(payload),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || ''
                    },
                    success: function (res) {
                        $('#modal-loading').hide();

                        if (res && res.success) {
                            try { localStorage.removeItem('positems'); } catch (e) { console.warn(e); }

                            if (res.invoice_html) {
                                // 1) inject invoice HTML to modal for preview (optional)
                                $('#invoice-container').html(res.invoice_html);

                                // 2) show modal for quick preview (backdrop false avoids overlay issues)
                                $('#invoice-modal').modal({ backdrop: false, keyboard: false });
                                $('#invoice-modal').modal('show');

                                // 3) print via hidden iframe for reliability
                                // remove existing iframe if present
                                var existing = document.getElementById('print-iframe');
                                if (existing) existing.parentNode.removeChild(existing);

                                var iframe = document.createElement('iframe');
                                iframe.id = 'print-iframe';
                                // keep it off-screen and invisible
                                iframe.style.position = 'fixed';
                                iframe.style.right = '0';
                                iframe.style.bottom = '0';
                                iframe.style.width = '0';
                                iframe.style.height = '0';
                                iframe.style.border = '0';
                                iframe.style.overflow = 'hidden';
                                iframe.style.opacity = '0';
                                document.body.appendChild(iframe);

                                // Write the invoice HTML into iframe (use srcdoc if available)
                                var doc = iframe.contentWindow || iframe.contentDocument;
                                if (iframe.contentDocument) doc = iframe.contentDocument;
                                doc.open();
                                // inject base tag so relative assets resolve (optional)
                                // plus small inline script that triggers print and notifies parent
                                var html = res.invoice_html;
                                // add a small script to call print then notify parent
                                html += '<script>window.onload = function(){ try{ window.focus(); setTimeout(function(){ window.print(); }, 200); }catch(e){} }; window.onafterprint = function(){ try{ parent.postMessage({ type: \"invoice-printed\" }, \"*\"); }catch(e){} }<\/script>';
                                doc.write(html);
                                doc.close();

                                // Listen for message from iframe (onafterprint) to clean up & close modal
                                function handleMsg(e) {
                                    if (!e.data) return;
                                    if (e.data.type && e.data.type === 'invoice-printed') {
                                        // remove listener
                                        window.removeEventListener('message', handleMsg);
                                        // remove iframe
                                        try { var f = document.getElementById('print-iframe'); if (f) f.parentNode.removeChild(f); } catch (err) { }
                                        // hide modal after short delay (so user sees preview)
                                        setTimeout(function () {
                                            $('#invoice-modal').modal('hide');
                                        }, 400);
                                    }
                                }
                                window.addEventListener('message', handleMsg);

                                // fallback: also close after 6 seconds if no message
                                setTimeout(function () {
                                    try {
                                        var f = document.getElementById('print-iframe');
                                        if (f) f.parentNode.removeChild(f);
                                    } catch (err) { }
                                    $('#invoice-modal').modal('hide');
                                    window.removeEventListener('message', handleMsg);
                                }, 7000);

                            } else {
                                bootbox && bootbox.alert
                                    ? bootbox.alert('Sale saved (ID: ' + res.sale_id + ')')
                                    : alert('Sale saved (ID: ' + res.sale_id + ')');
                            }

                            if (typeof loadItems === 'function') loadItems();
                        } else {
                            bootbox && bootbox.alert
                                ? bootbox.alert('Failed to save sale: ' + (res && res.message ? res.message : 'Unknown'))
                                : alert('Failed to save sale');
                        }
                    },


                    error: function (xhr) {
                        $('#modal-loading').hide();
                        console.error('AJAX error saving sale:', xhr);
                        var msg = 'Error saving sale. Check console/network.';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        bootbox && bootbox.alert ? bootbox.alert(msg) : alert(msg);
                    }
                });
            });







            // Get current date and time
            const now = new Date();

            // Format as dd/mm/yyyy hh:mm
            const day = String(now.getDate()).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const year = now.getFullYear();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            const formatted = `${day}/${month}/${year} ${hours}:${minutes}`;

            // Set input value
            document.getElementById('sldate').value = formatted;


            function updateClock() {
                const now = new Date();

                // Format: HH:MM:SS
                const day = String(now.getDate()).padStart(2, '0');
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const year = now.getFullYear();
                let h = now.getHours().toString().padStart(2, '0');
                let m = now.getMinutes().toString().padStart(2, '0');
                let s = now.getSeconds().toString().padStart(2, '0');

                document.getElementById('display_time').innerText = `${day}/${month}/${year}/${h}:${m}:${s}`;
            }

            // Update time every second
            setInterval(updateClock, 1000);

            // Run immediately
            updateClock();






        })();
    </script>



</body>

</html>