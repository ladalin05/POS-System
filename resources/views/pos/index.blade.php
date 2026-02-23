<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>POS Module | Mealea Spa</title>
    <script type="text/javascript">if (parent.frames.length !== 0) { top.location = ''; }</script>
    <meta name="viewport" content="user-scalable=no" />
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <link rel="shortcut icon" href="themes/default/assets/images/icon.png" />
    <link rel="stylesheet" href="{{ asset('assets/styles/theme.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/styles/style.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/pos/css/posajax.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/pos/css/print.css') }}" type="text/css" media="print" />
    <script type="text/javascript" src="{{ asset('assets/js_pos/jquery-2.0.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js_pos/jquery-migrate-1.2.1.min.js') }}"></script>

</head>

<body>
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
                        <span class="pos-logo-lg">Mealea Spa</span>
                        <span class="pos-logo-sm">POS</span>
                    </span>
                </a>
                <div class="header-nav">
                    <ul class="nav navbar-nav pull-right">
                        <li class="dropdown">
                            <a class="account dropdown-toggle" data-toggle="dropdown" href="#">
                                <div class="user">
                                    <span>Codentech</span>
                                </div>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="auth/profile/21">
                                        <i class="fa fa-user"></i> Profile </a>
                                </li>
                                <li>
                                    <a href="auth/profile/21/#cpassword">
                                        <i class="fa fa-key"></i> Change Password </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="auth/logout">
                                        <i class="fa fa-sign-out"></i> Logout </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav pull-right">
                        <li class="dropdown">
                            <a class=" pos-tip" title="Dashboard" data-placement="bottom" href="welcome">
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

                        <li><a class="pos-tip cl-danger" href="pos/add_table" style="color:#ffffff;font-weight:bold;"><i
                                    class="fa fa-toggle-off" aria-hidden="true"></i></a></li>


                        <li class="dropdown">
                            <a class="pos-tip" id="opened_bills" title="Suspended Sales" data-placement="bottom"
                                data-html="true" href="pos/opened_bills" data-toggle="ajax">
                                <i class="fa fa-th"></i>
                                <span class="posnumber white">1</span> </a>
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
                            <a target="_blank" class="pos-tip" id="add_expense" title="Add Expense"
                                href="purchases/add_expense">
                                <i class="fa fa-dollar"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a class="pos-tip" id="today_sale" title="Today's Sale" data-placement="bottom"
                                data-html="true" href="pos/today_sale" data-toggle="modal" data-target="#myModal">
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
                                            <!-- <input type="hidden" name="saleman_id" id="saleman_id" /> -->

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
                                                            <a disabled class="btn cl-primary"><i class="fa fa-retweet"
                                                                    aria-hidden="true"></i> Move</a>
                                                            <a disabled class="btn cl-primary"><i class="fa fa-times"
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
                                                            <button enabled type="button"
                                                                class="btn cl-primary btn-block" id="print_bill">
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
                                        <span id="hidesuspend"></span>
                                        <input type="hidden" name="pos_note" value="" id="pos_note">
                                        <input type="hidden" name="staff_note" value="" id="staff_note">
                                        <input type="hidden" name="award_point" value="" id="award_point">

                                        <input name="camount[]" id="camount_0" type="hidden" value="" />
                                        <input name="currency[]" type="hidden" value="USD" />
                                        <input name="rate[]" type="hidden" value="1" />

                                        <input name="camount[]" id="camount_1" type="hidden" value="" />
                                        <input name="currency[]" type="hidden" value="KHR" />
                                        <input name="rate[]" type="hidden" value="4100" />
                                        <div id="payment-con">
                                            <input type="hidden" name="amount[]" id="amount_val_1" value="" />
                                            <input type="hidden" name="balance_amount[]" id="balance_amount_1"
                                                value="" />
                                            <input type="hidden" name="paid_by[]" id="paid_by_val_1" value="" />
                                            <input type="hidden" name="cc_no[]" id="cc_no_val_1" value="" />
                                            <input type="hidden" name="paying_gift_card_no[]"
                                                id="paying_gift_card_no_val_1" value="" />
                                            <input type="hidden" name="cc_holder[]" id="cc_holder_val_1" value="" />
                                            <input type="hidden" name="cheque_no[]" id="cheque_no_val_1" value="" />
                                            <input type="hidden" name="cc_month[]" id="cc_month_val_1" value="" />
                                            <input type="hidden" name="cc_year[]" id="cc_year_val_1" value="" />
                                            <input type="hidden" name="cc_type[]" id="cc_type_val_1" value="" />
                                            <input type="hidden" name="cc_cvv2[]" id="cc_cvv2_val_1" value="" />
                                            <input type="hidden" name="payment_note[]" id="payment_note_val_1"
                                                value="" />
                                            <input type="hidden" name="amount[]" id="amount_val_2" value="" />
                                            <input type="hidden" name="balance_amount[]" id="balance_amount_2"
                                                value="" />
                                            <input type="hidden" name="paid_by[]" id="paid_by_val_2" value="" />
                                            <input type="hidden" name="cc_no[]" id="cc_no_val_2" value="" />
                                            <input type="hidden" name="paying_gift_card_no[]"
                                                id="paying_gift_card_no_val_2" value="" />
                                            <input type="hidden" name="cc_holder[]" id="cc_holder_val_2" value="" />
                                            <input type="hidden" name="cheque_no[]" id="cheque_no_val_2" value="" />
                                            <input type="hidden" name="cc_month[]" id="cc_month_val_2" value="" />
                                            <input type="hidden" name="cc_year[]" id="cc_year_val_2" value="" />
                                            <input type="hidden" name="cc_type[]" id="cc_type_val_2" value="" />
                                            <input type="hidden" name="cc_cvv2[]" id="cc_cvv2_val_2" value="" />
                                            <input type="hidden" name="payment_note[]" id="payment_note_val_2"
                                                value="" />
                                            <input type="hidden" name="amount[]" id="amount_val_3" value="" />
                                            <input type="hidden" name="balance_amount[]" id="balance_amount_3"
                                                value="" />
                                            <input type="hidden" name="paid_by[]" id="paid_by_val_3" value="" />
                                            <input type="hidden" name="cc_no[]" id="cc_no_val_3" value="" />
                                            <input type="hidden" name="paying_gift_card_no[]"
                                                id="paying_gift_card_no_val_3" value="" />
                                            <input type="hidden" name="cc_holder[]" id="cc_holder_val_3" value="" />
                                            <input type="hidden" name="cheque_no[]" id="cheque_no_val_3" value="" />
                                            <input type="hidden" name="cc_month[]" id="cc_month_val_3" value="" />
                                            <input type="hidden" name="cc_year[]" id="cc_year_val_3" value="" />
                                            <input type="hidden" name="cc_type[]" id="cc_type_val_3" value="" />
                                            <input type="hidden" name="cc_cvv2[]" id="cc_cvv2_val_3" value="" />
                                            <input type="hidden" name="payment_note[]" id="payment_note_val_3"
                                                value="" />
                                        </div>
                                        <input name="order_tax" type="hidden" value="1" id="postax2">
                                        <input name="discount" type="hidden" value="" id="posdiscount">
                                        <input name="shipping" type="hidden" value="0" id="posshipping">
                                        <input type="hidden" name="rpaidby" id="rpaidby" value="cash"
                                            style="display: none;" />
                                        <input type="hidden" name="total_items" id="total_items" value="0"
                                            style="display: none;" />
                                        <input type="submit" id="submit_sale" value="Submit Sale"
                                            style="display: none;" />
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
                                                    <button type="button" value="{{ $c->id }}"
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
                                        @foreach($products as $p)
                                            <button id="product-{{ $p->id }}" type="button" value="{{ $p->id }}"
                                                class="btn-prni btn-default product pos-tip"
                                                title="{{ $p->code }} {{ $p->name }}" data-container="body">
                                                <img src="{{ $p->image ? asset($p->image) : asset('images/no_image.png') }}"alt="" />
                                                <span>{{ $p->name }}</span>
                                            </button>
                                        @endforeach
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
        <!--<button type="button" class="close open-category"><i class="fa fa-2x">&times;</i></button>-->
        <div id="category-list">

        </div>
    </div>
    <div id="subcategory-slider">
        <!--<button type="button" class="close open-category"><i class="fa fa-2x">&times;</i></button>-->
        <div id="subcategory-list">

        </div>
    </div>
    
    <div class="modal fade in" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel"
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
                                        <label for="sldate">Date</label> <input type="text" name="date"
                                            value="06/08/2025 13:02" class="form-control input-tip sldate" id="sldate"
                                            required="required" />
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="biller">Branch</label> <select name="biller" class="form-control"
                                            id="posbiller" required="required">
                                            <option value="3" selected="selected">Mealea Spa</option>
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
                                            <td width="50%" height="45" class="text-left bold">Paid Amount</td>
                                            <td class="text-right">
                                                <input name="camount[]" base_rate="1" rate="1" type="text"
                                                    class="form-control camount base_amount" class="text-right" />
                                            </td>
                                            <td class="text-right">
                                                <input name="camount[]" base_rate="1" rate="4100" type="text"
                                                    class="form-control camount " class="text-right" />
                                            </td>
                                        </tr>


                                        <tr>

                                            <td width="50%" height="45" class="text-left bold">Balance</td>

                                           @foreach ($currencies as $currency)
                                            <td class="text-right">
                                                <span class="balance balance_{{ $currency->id }}"
                                                    data-rate="{{ $currency->rate }}"
                                                    id="balance_{{ $currency->id }}">0</span>
                                            </td>
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
                                                    <label for="paid_by_1">Paying by</label> <select name="paid_by[]"
                                                        id="paid_by_1" class="form-control paid_by">
                                                        <option cash_type="cash" value="1" selected="selected">Cash
                                                        </option>
                                                        <option cash_type="bank" value="2">ABA</option>
                                                        <option cash_type="bank" value="4">Credit Card</option>
                                                        <option cash_type="deposit" value="deposit">Deposit</option>
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
                                <input type="text" class="form-control kb-pad" id="pdiscount" readonly>
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

    <div class="modal fade in" id="sckModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">
                            <i class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span>
                    </button>
                    <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;"
                        onclick="window.print();">
                        <i class="fa fa-print"></i> Print </button>
                    <h4 class="modal-title" id="mModalLabel">Shortcut Keys</h4>
                </div>
                <div class="modal-body" id="pr_popover_content">
                    <table class="table table-bordered table-striped table-condensed table-hover"
                        style="margin-bottom: 0px;">
                        <thead>
                            <tr>
                                <th>Shortcut Keys</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ctrl+F3</td>
                                <td>Focus Add Item Input</td>
                            </tr>
                            <tr>
                                <td>Ctrl+Shift+M</td>
                                <td>Add Manual Item to Sale</td>
                            </tr>
                            <tr>
                                <td>Ctrl+Shift+C</td>
                                <td>Customer Input</td>
                            </tr>
                            <tr>
                                <td>Ctrl+Shift+A</td>
                                <td>Add Customer</td>
                            </tr>
                            <tr>
                                <td>Ctrl+F11</td>
                                <td>Toggle Categories Slider</td>
                            </tr>
                            <tr>
                                <td>Ctrl+F12</td>
                                <td>Toggle Subcategories Slider</td>
                            </tr>
                            <tr>
                                <td>F4</td>
                                <td>Cancel Sale</td>
                            </tr>
                            <tr>
                                <td>F7</td>
                                <td>Suspend Sale</td>
                            </tr>
                            <tr>
                                <td>F9</td>
                                <td>Print items list</td>
                            </tr>
                            <tr>
                                <td>Enter</td>
                                <td>Finalize Sale</td>
                            </tr>
                            <tr>
                                <td>Ctrl+F1</td>
                                <td>Today's Sale</td>
                            </tr>
                            <tr>
                                <td>Ctrl+F2</td>
                                <td>Open Suspended Sales</td>
                            </tr>
                            <tr>
                                <td>Ctrl+F10</td>
                                <td>Close Register</td>
                            </tr>
                        </tbody>
                    </table>
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
    <span class="hidden" id="bill_company">Mealea Spa</span>
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
    <script type="text/javascript">
        var site = { "base_url": "https:\/\/codentech-cambodia.com\/vbms_mealea_spa\/", "settings": { "project": "0", "accounting": "0", "installment": "0", "logo": "login_logo.jpg", "logo2": "logo.jpg", "site_name": "Mealea Spa", "language": "english", "default_warehouse": "1", "accounting_method": "2", "default_currency": "USD", "default_tax_rate": "0", "rows_per_page": "10", "version": "3.0.2.26", "default_tax_rate2": "1", "dateformat": "5", "sales_prefix": "INV", "pos_prefix": "POS", "sale_order_prefix": null, "quote_prefix": null, "purchase_prefix": "PU", "purchase_request_prefix": null, "purchase_order_prefix": null, "transfer_prefix": "TR", "delivery_prefix": null, "payment_prefix": "RC", "qa_prefix": "QA", "ca_prefix": null, "jn_prefix": "JN", "return_prefix": "SR", "returnp_prefix": "PUR", "expense_prefix": "EXP", "ppayment_prefix": "PV", "convert_prefix": "CV", "count_stock_prefix": null, "cus_opening_prefix": "OC", "sup_opening_prefix": "OS", "assign_prefix": "AS", "us_prefix": null, "bill_prefix": "BL", "item_addition": "0", "theme": "default", "product_serial": "0", "default_discount": "1", "product_discount": "1", "discount_method": "1", "tax1": "0", "tax2": "1", "overselling": "1", "iwidth": "40000", "iheight": "40000", "twidth": "60", "theight": "60", "watermark": "0", "smtp_host": "pop.gmail.com", "bc_fix": "4", "auto_detect_barcode": "0", "captcha": "0", "reference_format": "2", "racks": "0", "attributes": "0", "product_expiry": "0", "decimals": "2", "qty_decimals": "2", "decimals_sep": ".", "thousands_sep": ",", "invoice_view": "0", "default_biller": "3", "rtl": "0", "each_spent": null, "ca_point": null, "each_sale": null, "sa_point": null, "sac": "0", "display_all_products": "1", "display_symbol": "1", "symbol": "$", "remove_expired": "0", "barcode_separator": "_", "set_focus": "1", "barcode_img": "1", "disable_editing": "30", "update_cost": "1", "car_operation": "0", "qty_operation": "0", "customer_deposit_alerts": "0", "price_group": "9", "default_payment_term": "0", "single_login": "0", "set_custom_field": "0", "login_time": "0", "supplier_prefix": "SUP", "customer_prefix": "CUS", "receive_prefix": null, "installment_alert_days": null, "installment_holiday": null, "installment_prefix": null, "pawn_prefix": null, "customer_stock_prefix": null, "reference_reset": "1", "credit_amount": "0", "show_warehouse_qty": "0", "default_cash": null, "pawn_return_prefix": "PWR", "pawn_purchase_prefix": "PWP", "pawn_receive_prefix": "PWPR", "pawn_send_prefix": "PWPS", "manual_category": "0", "manual_unit": "1", "payment_expense": "0", "product_formulation": "0", "customer_price": "0", "cv_prefix": null, "limit_print": "0", "retainearning_acc": null, "tl_prefix": null, "repair_prefix": null, "rus_prefix": null, "sale_tax_prefix": "TAX", "default_receivable_account": null, "default_payable_account": null, "default_floor": null, "alert_qty_by_warehouse": "0", "search_by_category": "0", "date_with_time": "1", "project_id": null, "cbm": "0", "br_prefix": null, "product_additional": "0", "io_prefix": null, "fuel_prefix": null, "app_prefix": null, "borrower_prefix": null, "product_commission": null, "foc": "0", "show_unit": "0", "show_qoh": "0", "sav_prefix": null, "sav_tr_prefix": null, "csm_prefix": null, "rcsm_prefix": null, "check_prefix": null, "approval_expense": "0", "cfuel_prefix": null, "cdn_prefix": null, "cer_prefix": null, "cmw_prefix": null, "cms_prefix": null, "receive_item_vat": "0", "csale_prefix": null, "product_license": "0", "default_cash_account": "1", "product_vat": "0", "cfe_prefix": null, "ccms_prefix": null, "cabsent_prefix": null, "moving_waitings": "0", "missions": "0", "fuel_expenses": "0", "errors": "0", "absents": "0", "rp_prefix": null, "default_program": "0", "auto_invoice": "0", "sticket_prefix": "", "testing_fee": "0", "default_customer": "1", "expense_request_prefix": "", "other_site_name": "", "trucking_prefix": null, "user_language": "english", "user_rtl": "0" }, "dateFormats": { "js_sdate": "dd\/mm\/yyyy", "php_sdate": "d\/m\/Y", "mysq_sdate": "%d\/%m\/%Y", "js_ldate": "dd\/mm\/yyyy hh:ii", "php_ldate": "d\/m\/Y H:i", "mysql_ldate": "%d\/%m\/%Y %H:%i" } }, pos_settings = { "pos_id": "1", "cat_limit": "22", "pro_limit": "25", "default_category": "13", "default_customer": "1", "default_biller": "3", "display_time": "1", "cf_title1": "", "cf_title2": "", "cf_value1": "", "cf_value2": "", "receipt_printer": "3", "cash_drawer_codes": "x1C", "focus_add_item": "Ctrl+F3", "add_manual_product": "Ctrl+Shift+M", "customer_selection": "Ctrl+Shift+C", "add_customer": "Ctrl+Shift+A", "toggle_category_slider": "Ctrl+F11", "toggle_subcategory_slider": "Ctrl+F12", "cancel_sale": "F4", "suspend_sale": "F7", "print_items_list": "F9", "finalize_sale": "Enter", "today_sale": "Ctrl+F1", "open_hold_bills": "Ctrl+F2", "close_register": "Ctrl+F10", "keyboard": "0", "pos_printers": null, "java_applet": "0", "product_button_color": "default", "tooltips": "0", "paypal_pro": "0", "stripe": "0", "rounding": "0", "char_per_line": "42", "pin_code": null, "purchase_code": "purchase_code", "envato_username": "envato_username", "version": "3.0.2.24", "after_sale_page": "0", "item_order": "0", "authorize": "0", "toggle_brands_slider": "", "remote_printing": "1", "printer": "3", "order_printers": "[\"3\"]", "auto_print": "0", "customer_details": "0", "table_enable": "1", "queue_enable": "0", "queue_number": "42", "queue_expiry": "0", "pos_ref": null, "pos_delivery": "0", "pos_redirect_order": "1", "pos_payment_sale_note": "0", "pos_multi_payment": "1", "pos_layout_fix": "1", "pos_category_fix": "1", "pos_order_display": "0", "pos_favorite_items": "0", "screen_display": "0", "quick_payable": "0", "allow_min_price": "1", "default_floor": "0", "pos_payment": "1", "quick_pos": "0", "edit_last_item": "", "after_print": "1", "close_register_with_products": "0" };
        var lang = {
            unexpected_value: 'Unexpected value provided!',
            select_above: 'Please select above first',
            r_u_sure: 'Are you sure?',
            bill: 'Bill',
            order: 'Order',
            total: 'Total',
            items: 'Items',
            discount: 'Discount',
            order_tax: 'Order Tax',
            grand_total: 'Grand Total',
            total_payable: 'Total Payable',
            rounding: 'Rounding',
            merchant_copy: 'Merchant Copy',
            description: 'Description',
            qty: 'Qty',
            unit_price: 'Unit Price',
            no: 'No',
            paid_l_t_payable: 'Paid amount is less than the payable amount. Please press OK to submit the sale.',
            x_total: 'Please add product before payment. Thank you!',

        };
    </script>

    <script type="text/javascript">
        var product_variant = 0, shipping = 0, p_page = 0, per_page = 0, tcp = "3", pro_limit = 25,
            brand_id = 0, obrand_id = 0, cat_id = "13", ocat_id = "13", sub_cat_id = 0, osub_cat_id,
            count = 1, an = 1, DT = 0,
            product_tax = 0, invoice_tax = 0, product_discount = 0, order_discount = 0, total_discount = 0, total = 0, total_paid = 0, grand_total = 0,
            KB = 0, tax_rates = [{ "id": "1", "name": "No Tax", "code": "NT", "rate": "0", "type": "2" }, { "id": "2", "name": "VAT @10%", "code": "VAT10", "rate": "10", "type": "1" }, { "id": "3", "name": "GST @6%", "code": "GST", "rate": "6", "type": "1" }, { "id": "4", "name": "VAT @20%", "code": "VT20", "rate": "20", "type": "1" }];
        var protect_delete = 0, billers = [{ "logo": "photo_2024-12-27_10-03.jpg", "company": "Mealea Spa" }], biller = { "logo": "photo_2024-12-27_10-03.jpg", "company": "Mealea Spa" }, pos_delorder = 0;
        var username = 'codentech', order_data = '', bill_data = '';
        var bill_id = '', user_id = '21';
        var kh_rate = '4100';
        var allow_min_price = "1";
        function widthFunctions(e) {
            var wh = $(window).height(),
                lth = $('#left-top').height(),
                lbh = $('#left-bottom').height()
            pbt = $("#panel-top").height(),
                pnb = $("#panel-bottom").height();
            $('#item-list').css("height", wh - pbt - pnb - 101);
            $('#item-list').css("min-height", 384);
            $('#left-middle').css("height", wh - lth - lbh - 102);
            $('#left-middle').css("min-height", 278);
            $('#product-list').css("height", wh - lth - lbh - 107);
            $('#product-list').css("min-height", 278);
            // For quick pos setting
            var b_top = $('#b-top').height(), b_bottom = $("#b-bottom").height();
            $('#b-middle').css("height", wh - b_top - b_bottom - 180);
        }
        $(window).bind("resize", widthFunctions);
        $(document).ready(function () {
            // var customers = [{
            //     id: 1,
            //     text: 'អតិថិជនទូទៅ'
            // }];
            $('#poscustomer')
                .val(localStorage.getItem('poscustomer'))
                .select2({
                    minimumInputLength: 1,
                    data: [],

                    // initSelection must return an ARRAY with a single item:
                    initSelection: function (element, callback) {
                        $.ajax({
                            type: "GET",
                            url: "/people/customer/getCustomer/" + $(element).val(),
                            dataType: "json",
                            success: function (data) {
                                callback(data[0]);
                            }
                        });
                    },

                    // AJAX lookup now calls your Laravel route on the same origin:
                    ajax: {
                        url: "/people/customer/suggestions",
                        dataType: 'json',
                        quietMillis: 15,
                        data: function (term, page) {
                            return {
                                term: term,
                                limit: 10
                            };
                        },
                        results: function (data, page) {
                            return {
                                results: data.results && data.results.length
                                    ? data.results
                                    : [{ id: '', text: 'No Match Found' }]
                            };
                        }
                    }
                });
            if (KB) {
                display_keyboards();

                var result = false, sct = '';
                $('#poscustomer').on('select2-opening', function () {
                    sct = '';
                    $('.select2-input').addClass('kb-text');
                    display_keyboards();
                    $('.select2-input').bind('change.keyboard', function (e, keyboard, el) {
                        if (el && el.value != '' && el.value.length > 0 && sct != el.value) {
                            sct = el.value;
                        }
                        if (!el && sct.length > 0) {
                            $('.select2-input').addClass('select2-active');
                            $.ajax({
                                type: "get",
                                async: false,
                                url: "people/customer/suggestions/" + sct,
                                dataType: "json",
                                success: function (res) {
                                    if (res.results != null) {
                                        $('#poscustomer').select2({ data: res }).select2('open');
                                        $('.select2-input').removeClass('select2-active');
                                    } else {
                                        bootbox.alert('no_match_found');
                                        $('#poscustomer').select2('close');
                                        $('#test').click();
                                    }
                                }
                            });
                        }
                    });
                });

                $('#poscustomer').on('select2-close', function () {
                    $('.select2-input').removeClass('kb-text');
                    $('#test').click();
                    $('select, .select').select2('destroy');
                    $('select, .select').select2({ minimumResultsForSearch: 7 });
                });
                $(document).bind('click', '#test', function () {
                    var kb = $('#test').keyboard().getkeyboard();
                    kb.close();
                    //kb.destroy();
                    $('#add-item').focus();
                });

            }
            $(document).on('change', '#project', function () {
                var sb = $(this).val();
                $('#project_id').val(sb);
            });
            $(document).on('change', '#posbiller', function () {
                var sb = $(this).val();
                $.each(billers, function () {
                    if (this.id == sb) {
                        biller = this;
                    }
                });
                $('#biller').val(sb);
            });

            $('#paymentModal').on('change', '#amount_1', function (e) {
                $('#amount_val_1').val($(this).val());
            });
            $('#paymentModal').on('blur', '#amount_1', function (e) {
            $('#paymentModal').on('blur', '#amount_1', function (e) {
                $('#amount_val_1').val($(this).val());
            });

            $('#paymentModal').on('select2-close', '#paid_by_1', function (e) {
                $('#paid_by_val_1').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_no_1', function (e) {
                $('#cc_no_val_1').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_holder_1', function (e) {
                $('#cc_holder_val_1').val($(this).val());
            });
            $('#paymentModal').on('change', '#gift_card_no_1', function (e) {
                $('#paying_gift_card_no_val_1').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_month_1', function (e) {
                $('#cc_month_val_1').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_year_1', function (e) {
                $('#cc_year_val_1').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_type_1', function (e) {
                $('#cc_type_val_1').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_cvv2_1', function (e) {
                $('#cc_cvv2_val_1').val($(this).val());
            });
            $('#paymentModal').on('change', '#cheque_no_1', function (e) {
                $('#cheque_no_val_1').val($(this).val());
            });
            $('#paymentModal').on('change', '#payment_note_1', function (e) {
                $('#payment_note_val_1').val($(this).val());
            });
            $('#paymentModal').on('change', '#amount_2', function (e) {
                $('#amount_val_2').val($(this).val());
            });
            $('#paymentModal').on('blur', '#amount_2', function (e) {
                $('#amount_val_2').val($(this).val());
            });

            $('#paymentModal').on('select2-close', '#paid_by_2', function (e) {
                $('#paid_by_val_2').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_no_2', function (e) {
                $('#cc_no_val_2').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_holder_2', function (e) {
                $('#cc_holder_val_2').val($(this).val());
            });
            $('#paymentModal').on('change', '#gift_card_no_2', function (e) {
                $('#paying_gift_card_no_val_2').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_month_2', function (e) {
                $('#cc_month_val_2').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_year_2', function (e) {
                $('#cc_year_val_2').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_type_2', function (e) {
                $('#cc_type_val_2').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_cvv2_2', function (e) {
                $('#cc_cvv2_val_2').val($(this).val());
            });
            $('#paymentModal').on('change', '#cheque_no_2', function (e) {
                $('#cheque_no_val_2').val($(this).val());
            });
            $('#paymentModal').on('change', '#payment_note_2', function (e) {
                $('#payment_note_val_2').val($(this).val());
            });
            $('#paymentModal').on('change', '#amount_3', function (e) {
                $('#amount_val_3').val($(this).val());
            });
            $('#paymentModal').on('blur', '#amount_3', function (e) {
                $('#amount_val_3').val($(this).val());
            });

            $('#paymentModal').on('select2-close', '#paid_by_3', function (e) {
                $('#paid_by_val_3').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_no_3', function (e) {
                $('#cc_no_val_3').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_holder_3', function (e) {
                $('#cc_holder_val_3').val($(this).val());
            });
            $('#paymentModal').on('change', '#gift_card_no_3', function (e) {
                $('#paying_gift_card_no_val_3').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_month_3', function (e) {
                $('#cc_month_val_3').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_year_3', function (e) {
                $('#cc_year_val_3').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_type_3', function (e) {
                $('#cc_type_val_3').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_cvv2_3', function (e) {
                $('#cc_cvv2_val_3').val($(this).val());
            });
            $('#paymentModal').on('change', '#cheque_no_3', function (e) {
                $('#cheque_no_val_3').val($(this).val());
            });
            $('#paymentModal').on('change', '#payment_note_3', function (e) {
                $('#payment_note_val_3').val($(this).val());
            });
            $('#paymentModal').on('change', '#amount_4', function (e) {
                $('#amount_val_4').val($(this).val());
            });
            $('#paymentModal').on('blur', '#amount_4', function (e) {
                $('#amount_val_4').val($(this).val());
            });

            $('#paymentModal').on('select2-close', '#paid_by_4', function (e) {
                $('#paid_by_val_4').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_no_4', function (e) {
                $('#cc_no_val_4').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_holder_4', function (e) {
                $('#cc_holder_val_4').val($(this).val());
            });
            $('#paymentModal').on('change', '#gift_card_no_4', function (e) {
                $('#paying_gift_card_no_val_4').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_month_4', function (e) {
                $('#cc_month_val_4').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_year_4', function (e) {
                $('#cc_year_val_4').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_type_4', function (e) {
                $('#cc_type_val_4').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_cvv2_4', function (e) {
                $('#cc_cvv2_val_4').val($(this).val());
            });
            $('#paymentModal').on('change', '#cheque_no_4', function (e) {
                $('#cheque_no_val_4').val($(this).val());
            });
            $('#paymentModal').on('change', '#payment_note_4', function (e) {
                $('#payment_note_val_4').val($(this).val());
            });
            $('#paymentModal').on('change', '#amount_5', function (e) {
                $('#amount_val_5').val($(this).val());
            });
            $('#paymentModal').on('blur', '#amount_5', function (e) {
                $('#amount_val_5').val($(this).val());
            });

            $('#paymentModal').on('select2-close', '#paid_by_5', function (e) {
                $('#paid_by_val_5').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_no_5', function (e) {
                $('#cc_no_val_5').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_holder_5', function (e) {
                $('#cc_holder_val_5').val($(this).val());
            });
            $('#paymentModal').on('change', '#gift_card_no_5', function (e) {
                $('#paying_gift_card_no_val_5').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_month_5', function (e) {
                $('#cc_month_val_5').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_year_5', function (e) {
                $('#cc_year_val_5').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_type_5', function (e) {
                $('#cc_type_val_5').val($(this).val());
            });
            $('#paymentModal').on('change', '#pcc_cvv2_5', function (e) {
                $('#cc_cvv2_val_5').val($(this).val());
            });
            $('#paymentModal').on('change', '#cheque_no_5', function (e) {
                $('#cheque_no_val_5').val($(this).val());
            });
            $('#paymentModal').on('change', '#payment_note_5', function (e) {
                $('#payment_note_val_5').val($(this).val());
            });

            $('#payment').click(function () {
                $("#sldate,#podate").datetimepicker({
                    format: site.dateFormats.js_ldate + ':ss',
                }).datetimepicker('update', new Date());
                var twt = formatDecimal((total + invoice_tax) - order_discount + shipping);
                if (an == 1) {
                    bootbox.alert('Please add product before payment. Thank you!');
                    return false;
                }
                gtotal = formatDecimal(twt, 2);
                $('#twt').text(formatMoney(gtotal));
                $('#quick-payable').text(gtotal);
                $('#item_count, .item_count').text(count - 1);
                $('#paymentModal').appendTo("body").modal('show');
                $('#amount_1').focus();
            });
            $('#paymentModal').on('show.bs.modal', function (e) {
                $('#submit-sale').text('Submit').attr('disabled', false);
            });
            $('#paymentModal').on('shown.bs.modal', function (e) {
                $(".base_amount").val(total_paid != 0 ? total_paid : "").trigger("keyup").focus();
            });

            var pi = 'amount_1', pa = 2;
            $(document).on('click', '.quick-cash', function () {
                var $quick_cash = $(this);
                var amt = $quick_cash.contents().filter(function () {
                    return this.nodeType == 3;
                }).text();
                var th = ',';
                var $pi = $('#' + pi);
                amt = formatDecimal(amt.split(th).join(""), 2) * 1 + $pi.val() * 1;
                $pi.val(formatDecimal(amt, 2)).focus();
                $(".base_amount").val(formatDecimal(amt, 2)).trigger("keyup");
                var note_count = $quick_cash.find('span');
                if (note_count.length == 0) {
                    $quick_cash.append('<span class="badge">1</span>');
                } else {
                    note_count.text(parseInt(note_count.text()) + 1);
                }
            });

            $(document).on('click', '#clear-cash-notes', function () {
                $('.quick-cash').find('.badge').remove();
                $('#' + pi).val('0').focus();
                $(".base_amount").val('0').trigger("keyup");
            });

            $(document).on('change', '.gift_card_no', function () {
                var cn = $(this).val() ? $(this).val() : '';
                var payid = $(this).attr('id'),
                    id = payid.substr(payid.length - 1);
                if (cn != '') {
                    $.ajax({
                        type: "get", async: false,
                        url: site.base_url + "sales/validate_gift_card/" + cn,
                        dataType: "json",
                        success: function (data) {
                            if (data === false) {
                                $('#gift_card_no_' + id).parent('.form-group').addClass('has-error');
                                bootbox.alert('Gift card number is incorrect or expired.');
                            } else if (data.customer_id !== null && data.customer_id !== $('#poscustomer').val()) {
                                $('#gift_card_no_' + id).parent('.form-group').addClass('has-error');
                                bootbox.alert('Gift card number is not for this customer.');
                            } else {
                                $('#gc_details_' + id).html('<small>Card No: ' + data.card_no + '<br>Value: ' + data.value + ' - Balance: ' + data.balance + '</small>');
                                $('#gift_card_no_' + id).parent('.form-group').removeClass('has-error');
                                //calculateTotals();
                                $('#amount_' + id).val(gtotal >= data.balance ? data.balance : gtotal).focus();
                            }
                        }
                    });
                }
            });

            $(document).on('click', '.addButton', function () {
                if (pa <= 5) {
                    $('#paid_by_1, #pcc_type_1').select2('destroy');
                    var phtml = $('#payments').html(),
                        update_html = phtml.replace(/_1/g, '_' + pa);
                    pi = 'amount_' + pa;
                    $('#multi-payment').append('<button type="button" class="close close-payment" style="margin: -10px 0px 0 0;"><i class="fa fa-1x" style="font-weight:bold;">&times;</i></button>' + update_html);
                    $('#paid_by_1, #pcc_type_1, #paid_by_' + pa + ', #pcc_type_' + pa).select2({ minimumResultsForSearch: 7 });
                    read_card();
                    pa++;
                } else {
                    bootbox.alert('Max allowed limit reached.');
                    return false;
                }
                display_keyboards();
                $('#paymentModal').css('overflow-y', 'scroll');
            });

            $(document).on('click', '.addMorePayment', function () {
                var multi_payment = '<tr><th><select name="m_paid_by[]" class="form-control m_paid_by">';
                multi_payment += '<option cash_type="cash" value="1"  selected="selected">Cash</option><option cash_type="bank" value="2" >ABA</option><option cash_type="bank" value="4" >Credit Card</option>';
                multi_payment += '</select></th>';
                multi_payment += '<th><input type="text" name="m_qpaying_usd[]" class="form-control text-right m_qpaying_usd"/></th>';
                multi_payment += '<th><input type="text" name="m_qpaying_khr[]" class="form-control text-right m_qpaying_khr"/></th></tr>';
                $('#rowMultiPayment').after(multi_payment);
            });


            $(document).on('click', '.close-payment', function () {
                $(this).next().remove();
                $(this).remove();
                pa--;
            });

            $(document).on('click', '#pos_payment', function () {
                event.preventDefault();
                $('#submit_pos_payment').trigger("click");
                $(this).prop('disabled', true);

            });

            /************Updated Currencies***************/

            /*$(".paid_by").on("change",function(){
                var paid_by = $(this).val();
                if(paid_by != "cash"){
                    $(".payment-cash").hide();
                }else{
                    $(".payment-cash").show();
                }
            });*/

            $(".payment").on("click", function () {
                var decimal = site.settings.decimals;
                var amount_1 = $("#amount_1").val();
                $(".total_payable").each(function () {
                    var base_rate = $(this).attr("base_rate") - 0;
                    var rate = $(this).attr("rate") - 0;
                    var payable = (formatDecimal((total + invoice_tax) - order_discount + shipping) / base_rate) * rate;
                    if (rate > 1000) {
                        payable = formatMoneyKH(Math.round(payable / 100) * 100);
                    } else {
                        payable = formatMoney(payable);
                    }
                    $(this).text(payable);
                });

                $(".camount").on("keyup", function () {
                    var tamount = 0, i = 0;
                    $(".camount").each(function () {
                        var amount = $(this).val() - 0;
                        var base_rate = $(this).attr("base_rate") - 0;
                        var rate = $(this).attr("rate") - 0;
                        var camount = (amount / rate) * base_rate;
                        tamount += camount;
                        $("#camount_" + i).val(amount);
                        i++;
                    });

                    var award_point = $("#award_point").val() - 0;
                    if (!isNaN(award_point)) {
                        tamount += award_point;
                    }


                    $(".balance_1").each(function () {
                        var base_rate = $(this).attr("base_rate") - 0;
                        var rate = $(this).attr("rate") - 0;
                        var balance_1 = (((total - order_discount + shipping) - tamount) / base_rate) * rate;
                        if (rate > 1000) {
                            balance_1 = formatMoneyKH(balance_1);
                        } else {
                            balance_1 = formatMoney(balance_1);
                        }
                        $(this).text(balance_1);
                        i++;
                    });
                    $("#amount_1").val(formatDecimal(tamount, decimal)).trigger("focus keyup keypress");
                    $('#amount_val_1').val(formatDecimal(tamount, decimal));
                    calculateTotals();
                });

            });

            $(".camount").keydown(function (e) {
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                    return;
                }
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
            $('.camount').keydown(function (e) {
                if ($(this).val().length >= 16) {
                    $(this).val($(this).val().substr(0, 16));
                }
            });
            $('.camount').keyup(function (e) {
                if ($(this).val().length >= 16) {
                    $(this).val($(this).val().substr(0, 16));
                }
            });

            /************End Updated Currencies**************/

            $(document).on('focus keyup keypress', '.amount', function () {
                pi = $(this).attr('id');
                calculateTotals();
            }).on('blur keyup keypress', '.amount', function () {
                calculateTotals();
            });

            function calculateTotals() {
                var total_paying = 0;
                var ia = $(".amount");
                $.each(ia, function (i) {
                    var this_amount = formatCNum($(this).val() ? $(this).val() : 0);
                    total_paying += parseFloat(this_amount);
                });
                $('#total_paying').text(formatMoney(total_paying));
                $('#balance').text(formatMoney(gtotal - total_paying));
                $('#balance_' + pi).val(formatDecimal(total_paying - gtotal));
                total_paid = total_paying;
                grand_total = gtotal;

                var change = total_paid - grand_total;
                if (change > 0) {
                    change = change.toString();
                    if (change.indexOf(".") >= 0) {
                        var res = change.split(".");
                        var change_usd = formatDecimal(res[0], 4);
                        var change_riel = formatDecimal(("0." + res[1]), 4) * kh_rate;
                        if (change_riel > 0) {
                            change_riel = Math.round(change_riel / 100);
                            change_riel = change_riel * 100;
                        }
                        $("#change_usd").html(formatMoney(change_usd));
                        $("#change_riel").html(change_riel + " ៛");
                    } else {
                        $("#change_usd").html(formatMoney(change));
                        $("#change_riel").html(0);
                    }
                } else {
                    $("#change_usd").html(formatMoney(0));
                    $("#change_riel").html(0);
                }



            }

            $("#add_item").autocomplete({
                source: function (request, response) {
                    if (!$('#poscustomer').val()) {
                        $('#add_item').val('').removeClass('ui-autocomplete-loading');
                        bootbox.alert('Please select above first');
                        $('#add_item').focus();
                        return false;
                    }
                    $.ajax({
                        type: 'get',
                        url: 'sales/suggestions',
                        dataType: "json",
                        data: {
                            term: request.term,
                            biller_id: $("#posbiller").val(),
                            warehouse_id: $("#poswarehouse").val(),
                            customer_id: $("#poscustomer").val()
                        },
                        success: function (data) {
                            $(this).removeClass('ui-autocomplete-loading');
                            response(data);
                        }
                    });
                },
                minLength: 1,
                autoFocus: false,
                delay: 250,
                response: function (event, ui) {
                    if (ui.content.length == 1 && ui.content[0].id != 0) {
                        ui.item = ui.content[0];
                        $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                        $(this).autocomplete('close');
                    }
                },
                select: function (event, ui) {
                    event.preventDefault();
                    if (ui.item.id !== 0) {
                        // var product_type = ui.item.row.type;
                        // if (product_type == 'digital') {
                        $.ajax({
                            type: 'get',
                            url: 'sales/suggestionsDigital',
                            dataType: "json",
                            data: {
                                term: ui.item.item_id,
                                warehouse_id: $("#slwarehouse").val(),
                                customer_id: $("#slcustomer").val(),
                            },
                            success: function (result) {
                                $.each(result, function (key, value) {
                                    var row = add_invoice_item(value);
                                    if (row)
                                        $(this).val('');
                                });
                            }
                        });
                        $(this).val('');
                        //     } else {
                        //         var row = add_invoice_item(ui.item);
                        //         if (row)
                        //             $(this).val('');
                        //     }
                        // } else {
                        //     bootbox.alert('No matching result found! Product might be out of stock in the selected warehouse.');
                        // }
                    }
                }
            });
            // $('#posTable').stickyTableHeaders({fixedOffset: $('#product-list')});
            $('#posTable').stickyTableHeaders({ scrollableArea: $('#product-list') });
            $('#product-list,#brands-list, #category-list, #subcategory-list').perfectScrollbar({ suppressScrollX: true });
            
            $('select, .select').select2({ minimumResultsForSearch: 7 });

            $(document).on('click', '.product', function (e) {
                $('#modal-loading').show();
                code = $(this).val(),
                    bl = $('#posbiller').val(),
                    wh = $('#poswarehouse').val(),
                    cu = $('#poscustomer').val();
                $.ajax({
                    type: "get",
                    url: "pos/getProductDataByCode",
                    data: { code: code, biller_id: bl, warehouse_id: wh, customer_id: cu },
                    dataType: "json",
                    success: function (data) {
                        e.preventDefault();
                        if (data !== null) {
                            if (data.id !== 0) {
                                // var product_type = data.row.type;
                                // if (product_type == 'digital') {
                                $.ajax({
                                    type: 'get',
                                    url: 'sales/suggestionsDigital',
                                    dataType: "json",
                                    data: {
                                        term: data.item_id,
                                        warehouse_id: $("#slwarehouse").val(),
                                        customer_id: $("#slcustomer").val(),
                                    },
                                    success: function (result) {
                                        $.each(result, function (key, value) {
                                            var row = add_invoice_item(value);
                                            if (row)
                                                $(this).val('');
                                        });
                                    }
                                });
                                $(this).val('');
                                // }
                                // else {
                                var row = add_invoice_item(data);
                                if (row)
                                    $(this).val('');

                                // }
                            }
                            // else {
                            //     bootbox.alert('No matching result found! Product might be out of stock in the selected warehouse.');
                            // }
                            $('#modal-loading').hide();
                        }
                        // else {
                        //     bootbox.alert('No matching result found! Product might be out of stock in the selected warehouse.');
                        //     $('#modal-loading').hide();
                        // }
                    }
                });
            });

            $(document).on('click', '#favorite', function () {
                if (cat_id != $(this).val()) {
                    $(".sp_code").val("");
                    $(".sp_name").val("");
                    var sp_favorite = $(".sp_favorite").val() == 1 ? 0 : 1;
                    $.ajax({
                        type: "get",
                        url: "{{ route('pos.ajaxCategoryData') }}",
                        data: {
                            category_id: cat_id,
                            sp_favorite: sp_favorite,
                        },
                        dataType: "json",
                        success: function (data) {
                            $('#item-list').empty();
                            var newPrs = $('<div></div>');
                            newPrs.html(data.products);
                            newPrs.appendTo("#item-list");
                            $('#subcategory-list').empty();
                            var newScs = $('<div></div>');
                            newScs.html(data.subcategories);
                            newScs.appendTo("#subcategory-list");
                            tcp = data.tcp;
                            // nav_pointer();
                        }
                    }).done(function () {
                        p_page = 'n';
                        $('#category-' + cat_id).addClass('active');
                        $('#category-' + ocat_id).removeClass('active');
                        ocat_id = cat_id;
                        $('#modal-loading').hide();
                        // nav_pointer();
                        $(".sp_favorite").val(sp_favorite);
                    });
                }
            });
            $('#random_num').click(function () {
                $(this).parent('.input-group').children('input').val(generateCardNo(8));
            });


            $(document).on('change keypress keyup', '.sp_code,.sp_name', function () {
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
                            var newPrs = $('<div></div>');
                            newPrs.html(data.products);
                            newPrs.appendTo("#item-list");
                            $('#subcategory-list').empty();
                            var newScs = $('<div></div>');
                            newScs.html(data.subcategories);
                            newScs.appendTo("#subcategory-list");
                            tcp = data.tcp;
                            // nav_pointer();
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

            $(document).on('click', '.category', function () {
                if (cat_id != $(this).val()) {
                    if (!$(this).attr("disabled-open-category")) {
                        $('#open-category').click();
                    }
                    $(".category").removeClass("cl-danger");
                    $(this).addClass("cl-danger");
                    sub_cat_id = 0;

                    $('#modal-loading').show();
                    cat_id = $(this).val();

                    $.ajax({
                        type: "get",
                        url: "{{ route('pos.ajaxCategoryData') }}",
                        data: { category_id: cat_id },
                        dataType: "json",
                        success: function (data) {
                            $('#item-list').empty().append($('<div></div>').html(data.products));

                            $('#subcategory-list').empty().append($('<div></div>').html(data.subcategories));
                            $('#pos-subcategories').empty().append($('<span></span>').html(data.subcategories));

                            tcp = data.tcp;
                            // nav_pointer();
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


            $('#category-' + cat_id).addClass('active');

            $(document).on('click', '.brand', function () {
                if (brand_id != $(this).val()) {
                    $('#open-brands').click();
                    $('#modal-loading').show();
                    brand_id = $(this).val();
                    $.ajax({
                        type: "get",
                        url: "pos/ajaxbranddata",
                        data: { brand_id: brand_id },
                        dataType: "json",
                        success: function (data) {
                            $('#item-list').empty();
                            var newPrs = $('<div></div>');
                            newPrs.html(data.products);
                            newPrs.appendTo("#item-list");
                            tcp = data.tcp;
                            // nav_pointer();
                        }
                    }).done(function () {
                        p_page = 'n';
                        $('#brand-' + brand_id).addClass('active');
                        $('#brand-' + obrand_id).removeClass('active');
                        obrand_id = brand_id;
                        $('#category-' + cat_id).removeClass('active');
                        $('#subcategory-' + sub_cat_id).removeClass('active');
                        cat_id = 0; sub_cat_id = 0;
                        $('#modal-loading').hide();
                        // nav_pointer();
                    });
                }
            });

            $(document).on('click', '.subcategory', function () {
                if (sub_cat_id != $(this).val()) {

                    $(".subcategory").removeClass("cl-danger");
                    $(this).addClass("cl-danger");

                    $('#open-subcategory').click();
                    $('#modal-loading').show();
                    sub_cat_id = $(this).val();
                    $.ajax({
                        type: "get",
                        url: "{{ route('pos.ajaxProducts') }}",
                        data: { category_id: cat_id, subcategory_id: sub_cat_id, per_page: p_page },
                        dataType: "html",
                        success: function (data) {
                            $('#item-list').empty();
                            var newPrs = $('<div></div>');
                            newPrs.html(data);
                            newPrs.appendTo("#item-list");
                        }
                    }).done(function () {
                        p_page = 'n';
                        $('#subcategory-' + sub_cat_id).addClass('active');
                        $('#subcategory-' + osub_cat_id).removeClass('active');
                        $('#modal-loading').hide();
                    });
                }
            });

            var per_page_c = 0;
            var total_row_c = "13" - 0;
            var category_row = "15" - 0;

            $('#next_c').click(function () {
                per_page_c += category_row;
                if (per_page_c < 0) {
                    per_page_c = 0;
                }
                $.ajax({
                    type: "get",
                    url: "{{ route('pos.ajaxCategories') }}",
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

            $('#previous_c').click(function () {
                per_page_c -= category_row;
                if (per_page_c < 0) {
                    per_page_c = 0;
                }
                $.ajax({
                    type: "get",
                    url: "{{ route('pos.ajaxCategories') }}",
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
         

            cpointer();

            function cpointer() {
                // disable Next if you’ve hit the end…
                if (per_page_c >= total_row_c || total_row_c <= per_page_c + category_row) {
                    $('#next_c').attr("disabled", true);
                } else {
                    $('#next_c').removeAttr("disabled");
                }
                // disable Prev if we’re at zero
                if (per_page_c > 0) {
                    $('#previous_c').removeAttr("disabled");
                } else {
                    $('#previous_c').attr("disabled", true);
                }
            }


            $('#next').click(function () {
                var sp_code = $(".sp_code").val().trim();
                var sp_name = $(".sp_name").val().trim();
                var sp_favorite = $(".sp_favorite").val().trim();
                if (p_page == 'n') {
                    p_page = 0
                }
                p_page = p_page + pro_limit;
                if (tcp >= pro_limit && p_page < tcp) {
                    $('#modal-loading').show();
                    $.ajax({
                        type: "GET",
                        url: "{{ route('pos.ajaxProducts') }}",
                        data: {
                            category_id: cat_id,
                            subcategory_id: sub_cat_id,
                            per_page: p_page,
                            sp_code: $(".sp_code").val().trim(),
                            sp_name: $(".sp_name").val().trim(),
                            sp_favorite: $(".sp_favorite").val().trim()
                        },
                        dataType: "json",   // change to json
                        success: function (data) {
                            $('#item-list').empty().append(data.html);
                            tcp = data.tcp;    // update your total count if needed
                            // nav_pointer();
                        }
                    }).done(function () {
                        $('#modal-loading').hide();
                    });
                } else {
                    p_page = p_page - pro_limit;
                }
            });

            $('#previous').click(function () {
                var sp_code = $(".sp_code").val().trim();
                var sp_name = $(".sp_name").val().trim();
                var sp_favorite = $(".sp_favorite").val().trim();
                if (p_page == 'n') {
                    p_page = 0;
                }
                if (p_page != 0) {
                    $('#modal-loading').show();
                    p_page = p_page - pro_limit;
                    if (p_page == 0) {
                        p_page = 'n'
                    }
                    $.ajax({
                        type: "GET",
                        url: "{{ route('pos.ajaxProducts') }}",
                        data: {
                            category_id: cat_id,
                            subcategory_id: sub_cat_id,
                            per_page: p_page,
                            sp_code: $(".sp_code").val().trim(),
                            sp_name: $(".sp_name").val().trim(),
                            sp_favorite: $(".sp_favorite").val().trim()
                        },
                        dataType: "json",   // change to json
                        success: function (data) {
                            $('#item-list').empty().append(data.html);
                            tcp = data.tcp;    // update your total count if needed
                            // nav_pointer();
                        }
                    }).done(function () {
                        $('#modal-loading').hide();
                    });
                }
            });

            $(document).on('change', '.paid_by', function () {
                var p_val = $(this).val(),
                    id = $(this).attr('id'),
                    pa_no = id.substr(id.length - 1);
                $('#rpaidby').val(p_val);
                if (p_val == 'gift_card') {
                    $('.gc_' + pa_no).show();
                    $('.ngc_' + pa_no).hide();
                    $('#gift_card_no_' + pa_no).focus();
                } else {
                    $('.ngc_' + pa_no).show();
                    $('.gc_' + pa_no).hide();
                    $('#gc_details_' + pa_no).html('');
                }
            });


            $(document).on('change', '#psalesman', function () {

                var psalesman = $(this).val();
                // alert(saleman_id);
                if (psalesman) {
                    $("#sale_man_id").val(saleman_id);
                }
            });


            $(document).on('click', '#submit-sale', function () {
                // alert(0);
                var delivery_status = $(".delivery_status option:selected").val();
                var pos_ref = $(".pos_ref").val();


                if (total_paid < gtotal) {

                    if (allow_min_price == 1) {

                        bootbox.confirm("Paid amount is less than the payable amount. Please press OK to submit the sale.", function (res) {
                            if (res == true) {
                                $("#pos_reference_no").val(pos_ref);
                                $("#delivery_status").val(delivery_status);
                                $('#pos_note').val(localStorage.getItem('posnote'));
                                $('#staff_note').val(localStorage.getItem('staffnote'));
                                $('#submit-sale').text('Loading...').attr('disabled', true);
                                $('#pos-sale-form').submit();
                            }
                        });
                        return false;

                    } else {
                        bootbox.alert("Paid amount is less than the payable amount. Please press OK to submit the sale.");
                        return false;
                    }

                } else {
                    $("#pos_reference_no").val(pos_ref);
                    $("#delivery_status").val(delivery_status);
                    $('#pos_note').val(localStorage.getItem('posnote'));
                    $('#staff_note').val(localStorage.getItem('staffnote'));
                    $(this).text('Loading...').attr('disabled', true);
                    $('#pos-sale-form').submit();
                }



            });

            $('#suspend').click(function () {
                if (count <= 1) {
                    bootbox.alert('Please add product before print bill. Thank you!');
                    return false;
                } else {
                    $('#susModal').modal();
                }
            });

            $(".delete_suspend").on("click", function () {
                var hreff = $(this).attr("hreff");
                bootbox.confirm("Are you sure you want to Cancel Sale?", function (result) {
                    if (result) {
                        location.href = hreff;
                    }
                });
                return false;
            });

            $('#suspend_sale').click(function () {
                ref = $('#reference_note').val();
                if (!ref || ref == '') {
                    bootbox.alert('Please type reference note and submit to suspend this sale');
                    return false;
                } else {
                    suspend = $('<span></span>');
                    suspend.html('<input type="hidden" name="suspend" value="yes" /><input type="hidden" name="suspend_note" value="' + ref + '" />');
                    suspend.appendTo("#hidesuspend");
                    $('#total_items').val(count - 1);
                    $('#pos-sale-form').submit();
                }
            });

        });

        $(document).ready(function () {

            $(document).off('click', '#print_order').on('click', '#print_order', function (e) {
                $(this).attr("disabled", "disabled");
                if (an == 1) {
                    bootbox.alert('Please add product before payment. Thank you!');
                    return false;
                }
                var an1 = "";
                if (!an1) {
                    bootbox.alert('Please select table.');
                    return false;
                }

                printOrder();
                update_bill();
                return false;

                Popup($('#order_tbl').html());
            });

            $(document).off('click', '#print_bill').on('click', '#print_bill', function (e) {
                if (an == 1) {
                    bootbox.alert('Please add product before payment. Thank you!');
                    return false;
                }
                // edition new version
                var allow = true;
                $(".suspend_item_id").each(function () {
                    var suspend_item_id = $(this).val();
                    if (suspend_item_id <= 0) {
                        allow = false;
                    }
                });
                if (!allow) {
                    bootbox.alert('Please add product before print bill. Thank you!');
                    return false;
                }

                Popup($('#bill_tbl').html());

                add_bill();


                return false;

                // old version
                Popup($('#bill_tbl').html());
            });

            function add_bill() {
                var bill_id = "";
                $.ajax({
                    url: "pos/add_bill",
                    data: { bill_id: bill_id },
                    success: function (data) {
                        var number_dynamic = parseFloat(data) + 1;
                        $("#bill_number").html(number_dynamic);
                    }
                });
            }

            number_bill();
            function number_bill(number) {
                var number_static = parseFloat("0") + 1;
                $("#bill_number").html(number_static);
            }

            $(".print_order").each(function () {
                var item = $(this).attr("data-item");
                $('#print_order_' + item).click(function () {
                    if (an == 1) {
                        bootbox.alert('Please add product before payment. Thank you!');
                        return false;
                    }
                    Popup($("#order_tbl_" + item).html());
                });
            });
        });

        $(function () {
            $(".alert").effect("shake");
            setTimeout(function () {
                $(".alert").hide('blind', {}, 500)
            }, 15000);
            var now = new moment();
            $('#display_time').text(now.format((site.dateFormats.js_sdate).toUpperCase() + " HH:mm"));
            setInterval(function () {
                var now = new moment();
                $('#display_time').text(now.format((site.dateFormats.js_sdate).toUpperCase() + " HH:mm"));
            }, 1000);
        });


        function Popup(data) {
            var mywindow = window.open('', 'pos_print', 'height=500,width=300');
            mywindow.document.write('<html><head><title>Print</title>');
            mywindow.document.write('<link rel="stylesheet" href="themes/default/assets/styles/helpers/bootstrap.min.css" type="text/css" />');
            mywindow.document.write('</head><body>');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');
            mywindow.print();
            mywindow.close();
            return true;
        }

        function update_bill() {
            if ($(".add_suspend_item").attr("id") > 0) {
                var table_id = $(".add_suspend_item").attr("id");
                var table_name = $(".add_suspend_item").val();
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: site.base_url + "pos",
                    data: $("#pos-sale-form").serialize(),
                    success: function (data) {
                        $.ajax({
                            url: "pos/update_bill/",
                            type: "GET",
                            dataType: "JSON",
                            data: { suspend_id: "" },
                            success: function (data) {
                                $(".item_ordered").val(data.count);
                                localStorage.setItem('positems', JSON.stringify(data.pr));
                                loadItems();
                                $("#print_order").removeAttr("disabled");
                                setInterval(function () {
                                    location.href = "pos/add_table";
                                }, 1000);
                            }
                        });
                    }
                });
            }
            if (localStorage.getItem('positems')) {
                sortedItems = JSON.parse(localStorage.getItem('positems'));
                $.each(sortedItems, function () {
                    var item = this;
                    var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
                    positems[item_id].row.ordered = 1;
                    localStorage.setItem('positems', JSON.stringify(positems));
                    loadItems();
                });
            }
        }

    </script>



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
    <script type="text/javascript" src="{{ asset('assets/pos/js/pos.ajax.js') }}"></script>

    <script type="text/javascript">

        var order_printers = [{ "id": "3", "title": "Printer 1", "type": "windows", "profile": "simple", "char_per_line": "30", "path": "smb:\/\/localhost\/POS-80C", "ip_address": "localhost", "port": null }];
        function printOrder() {
            $.each(order_printers, function () {
                var socket_data = {
                    'printer': this,
                    'logo': (biller && biller.logo ? biller.logo : ''),
                    'text': order_data
                };
                $.get('pos/p/order', { data: JSON.stringify(socket_data) });
            });
            return false;
        }
        function printBill() {
            var socket_data = {
                'printer': { "id": "3", "title": "Printer 1", "type": "windows", "profile": "simple", "char_per_line": "30", "path": "smb:\/\/localhost\/POS-80C", "ip_address": "localhost", "port": null },
                'logo': (biller && biller.logo ? biller.logo : ''),
                'text': bill_data
            };
            $.get('pos/p_bill', { data: JSON.stringify(socket_data), sid: "0", }).always(function () {
                $("#print_bill").removeAttr("disabled");
            });
            return false;
        }
    </script>

    <script type="text/javascript">
        $('.sortable_table tbody').sortable({
            containerSelector: 'tr'
        });
    </script>
    <script type="text/javascript"
        charset="UTF-8">(function ($) { "use strict"; $.fn.select2.locales['bms'] = { formatMatches: function (matches) { if (matches === 1) { return "One result is available, press enter to select it."; } return matches + "results are available, use up and down arrow keys to navigate."; }, formatNoMatches: function () { return "No matches found"; }, formatInputTooShort: function (input, min) { var n = min - input.length; return "Please type " + n + " or more characters"; }, formatInputTooLong: function (input, max) { var n = input.length - max; if (n == 1) { return "Please delete " + n + " character"; } else { return "Please delete " + n + " characters"; } }, formatSelectionTooBig: function (n) { if (n == 1) { return "You can only select " + n + " item"; } else { return "You can only select " + n + " items"; } }, formatLoadMore: function (pageNumber) { return "Loading more results..."; }, formatSearching: function () { return "Searching..."; }, formatAjaxError: function () { return "Ajax request failed"; }, }; $.extend($.fn.select2.defaults, $.fn.select2.locales['bms']); })(jQuery);</script>
    <div id="ajaxCall"><i class="fa fa-spinner fa-pulse"></i></div>
    <style type="text/css">
    </style>

    <script>
        $(document).ready(function () {


            $(".combo_product:not(.ui-autocomplete-input)").live("focus", function (event) {
                $(this).autocomplete({
                    source: 'products/suggestions',
                    minLength: 1,
                    autoFocus: false,
                    delay: 250,
                    response: function (event, ui) {
                        if (ui.content.length == 1 && ui.content[0].id != 0) {
                            ui.item = ui.content[0];
                            $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                            $(this).autocomplete('close');
                            $(this).removeClass('ui-autocomplete-loading');
                        }
                    },
                    select: function (event, ui) {
                        event.preventDefault();
                        if (ui.item.id !== 0) {
                            var parent = $(this).parent().parent();
                            parent.find(".combo_product_id").val(ui.item.id);
                            parent.find(".combo_name").val(ui.item.name);
                            parent.find(".combo_code").val(ui.item.code);
                            parent.find(".combo_price").val(formatDecimal(ui.item.price));
                            parent.find(".combo_qty").val(formatDecimal(1));
                            if (site.settings.qty_operation == 1) {
                                parent.find(".combo_width").val(formatDecimal(1));
                                parent.find(".combo_height").val(formatDecimal(1));
                            }
                            $(this).val(ui.item.label);
                        } else {
                            bootbox.alert('No matching result found! Product might be out of stock in the selected warehouse.');
                        }
                    }
                });
            });





            $("#posbiller").change(biller);


            function biller() {
                var biller = $("#posbiller").val();
                var project = "";
                $.ajax({
                    url: "pos/get_project",
                    type: "GET",
                    dataType: "JSON",
                    data: { biller: biller, project: project },
                    success: function (data) {
                        if (data) {
                            $(".no-project").html(data.result);
                            var project_id = $("#project").val();
                            $("#project").change(function () {
                                project_id = $(this).val();
                            });
                            $("#project_id").val(project_id);
                            $('select').select2();
                        }
                    }
                })
            }

            $("#sldate").on("change", function () {
                var podate = $(this).val();
                $("#podate").val(podate);
            });

            $("#sldate").datetimepicker({
                format: site.dateFormats.js_ldate + ':ss',
                fontAwesome: true,
                language: 'bms',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());

            var old_tp_discount;
            $('#tpdiscount').focus(function () {
                old_tp_discount = $(this).val();
            }).change(function () {
                var new_tp_discount = $(this).val() ? $(this).val() : '0';
                if (is_valid_discount(new_tp_discount)) {
                    var pro_total = $('#hpro_total').val() - 0;
                    if (new_tp_discount.indexOf("%") !== -1) {
                        var pds = new_tp_discount.split("%");
                        if (!isNaN(pds[0])) {
                            var discount = parseFloat(((pro_total) * parseFloat(pds[0])) / 100);
                        } else {
                            var discount = parseFloat(new_tp_discount);
                        }
                    } else {
                        var discount = parseFloat(new_tp_discount);
                    }

                    var pro_quantity = $('#pquantity').val() - 0;
                    var pro_price = $('#pprice').val() - 0;
                    var g_pro_total = pro_quantity * pro_price;
                    var n_pro_total = pro_total - discount;
                    var pro_discount = (g_pro_total - n_pro_total) / pro_quantity;

                    $('#pdiscount').val(pro_discount);
                    $('#pdiscount').change();
                    $(this).val('');
                    return;
                } else {
                    $(this).val(old_tp_discount);
                    bootbox.alert(lang.unexpected_value);
                    return;
                }
            });

            $('#posmembership_code').on("change blur keyup", function (e) {
                var membership_code = $(this).val();
                $.ajax({
                    url: site.base_url + "pos/get_membership_code",
                    dataType: "JSON",
                    type: "GET",
                    data: { membership_code: membership_code },
                    success: function (mm) {
                        var customer_id = parseInt(mm.customer_id) ? parseInt(mm.customer_id) : 0;
                        $("#poscustomer").val(customer_id).select2({
                            minimumInputLength: 1,
                            data: [],
                            initSelection: function (element, callback) {
                                $.ajax({
                                    type: "get", async: false,
                                    url: site.base_url + "people/customer/getCustomer/" + $(element).val(),
                                    dataType: "json",
                                    success: function (data) {
                                        callback(data[0]);
                                    }
                                });
                            },
                            ajax: {
                                url: site.base_url + "people/customer/suggestions",
                                dataType: 'json',
                                quietMillis: 15,
                                data: function (term, page) {
                                    return {
                                        term: term,
                                        limit: 10
                                    };
                                },
                                results: function (data, page) {
                                    if (data.results != null) {
                                        return { results: data.results };
                                    } else {
                                        return { results: [{ id: '', text: 'No Match Found' }] };
                                    }
                                }
                            }
                        });
                        if (mm.status == "success") {
                            $("#posmembership_code").css({ "color": "#428BCA", "font-weight": "bold" });
                            $("#poscustomer").prop("readonly", "readonly");
                        } else if (mm.status == "expired") {
                            $("#posmembership_code").css({ "color": "#FABB3D", "font-weight": "bold" });
                            $("#poscustomer").removeAttr("readonly");
                        } else {
                            $("#posmembership_code").css({ "color": "#C9302C", "font-weight": "bold" });
                            $("#poscustomer").removeAttr("readonly");
                        }
                    }
                });
            });

            $("#spinner-toggle").click(function () {
                $(".scroll-spinner").slideToggle("slow", function () { });
            });

            $(function () {
                $('input[type=text]').attr('autocomplete', 'off');
            });


            var old_award_point;
            $(document).on("focus", '.award_point', function () {
                old_award_point = $(this).val();
            }).on("change", '.award_point', function () {
                var t_award_point = $("#t_award_point").val() - 0;
                if ($(this).val() == '') {
                    $(this).val(0);
                } else if (!is_numeric($(this).val())) {
                    $(this).val(old_award_point);
                    return;
                } else if (($(this).val() - 0) > t_award_point) {
                    $(this).val(old_award_point);
                    bootbox.alert("Award Point cannot more than " + formatMoney(t_award_point));
                    return;
                }
                $("#award_point").val($(this).val());
                $(".camount").keyup();
            });

        });
    </script>
</body>

</html>
