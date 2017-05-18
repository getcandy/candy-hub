@extends('layout')

@section('side_menu')
    @include('catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>Catalogue Manager</small>
    <h1>Edit Product</h1>
@endsection

@section('header_actions')
    <button class="btn btn-success"><i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> Save Product</button>
    <div>
      <button class="btn btn-default white product-menu btn-pop-over"><span class="hamburger"></span></button>
      <!-- Menu Pop Over -->
      <div class="pop-over">
        <ul class="menu">
          <li><a href="#" title="Duplicate product">Duplicate</a></li>
          <li><a href="#" title="View product on live site">View</a></li>
        </ul>
      </div>
    </div>
    <button class="btn btn-default white"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
@endsection

@section('content')
        <!-- Search tabs -->
        <ul class="nav nav-tabs sub-nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#product-details" aria-controls="product-details" role="tab" data-toggle="tab">Product Details</a></li>
          <li role="presentation"><a href="#availability-pricing" aria-controls="availability-pricing" role="tab" data-toggle="tab">Availability &amp; Pricing</a></li>
          <li role="presentation"><a href="#collections" aria-controls="collections" role="tab" data-toggle="tab">Collections</a></li>
          <li role="presentation"><a href="#associations" aria-controls="associations" role="tab" data-toggle="tab">Associations</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="product-details">
            <div class="sub-panel">
              <div class="tab-content sub-content section block">
                <div role="tabpanel" class="tab-pane active" id="marketing">
                  <div class="row">
                    <div class="col-xs-12 col-md-10">
                      <div class="form-inline">
                        <div class="form-group">
                          <label class="sr-only">Store Channels</label>
                          <select class="form-control selectpicker">
                            <option data-content="<i class='fa fa-shopping-cart'></i> Storefront" selected>Store Front</option>
                            <option data-content="<i class='fa fa-shopping-bag'></i> eBay">eBay</option>
                            <option data-content="<i class='fa fa-facebook'></i> Facebook">Facebook</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label class="sr-only">Language</label>
                          <select class="form-control selectpicker">
                            <option selected>English</option>
                            <option>French</option>
                            <option>German</option>
                          </select>
                        </div>
                        <button class="btn btn-default">Translate</button>
                      </div>
                      <hr>
                      <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control"></textarea>
                      </div>
                    </div>
                  </div>

                  <!-- Translate Active
                  <div class="row">
                    <div class="col-xs-12 col-md-6">
                      <div class="form-inline">
                        <div class="form-group">
                          <label class="sr-only">Store Channels</label>
                          <select class="form-control selectpicker">
                            <option selected disabled>Store Channels</option>
                            <option>Store Front</option>
                            <option>eBay</option>
                            <option>Facebook</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label class="sr-only">Language</label>
                          <select class="form-control selectpicker">
                            <option selected>English</option>
                            <option>French</option>
                            <option>German</option>
                          </select>
                        </div>
                      </div>
                      <hr>
                      <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control"></textarea>
                      </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                      <div class="form-inline">
                        <div class="form-group">
                          <label class="sr-only">Store Channels</label>
                          <select class="form-control selectpicker">
                            <option selected disabled>Store Channels</option>
                            <option>Store Front</option>
                            <option>eBay</option>
                            <option>Facebook</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label class="sr-only">Language</label>
                          <select class="form-control selectpicker">
                            <option selected>English</option>
                            <option>French</option>
                            <option>German</option>
                          </select>
                        </div>
                        <button class="btn btn-default">Save</button>
                      </div>
                      <hr>
                      <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control"></textarea>
                      </div>
                    </div>
                  </div>  -->
                </div>

                <div role="tabpanel" class="tab-pane" id="tech">
                  Wireframes required
                </div>

                <div role="tabpanel" class="tab-pane" id="media">
                  Wireframes required
                </div>

                <div role="tabpanel" class="tab-pane" id="seo">
                  Wireframes required
                </div>
              </div>
              <!-- Refine tabs -->
              <ul class="nav nav-tabs secondary sub-nav" role="tablist">
                <li role="presentation" class="active"><a href="#marketing" aria-controls="marketing" role="tab" data-toggle="tab">Marketing</a></li>
                <li role="presentation"><a href="#tech" aria-controls="tech" role="tab" data-toggle="tab">Technical</a></li>
                <li role="presentation"><a href="#media" aria-controls="media" role="tab" data-toggle="tab">Media</a></li>
                <li role="presentation"><a href="#seo" aria-controls="seo" role="tab" data-toggle="tab">SEO</a></li>
              </ul>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane" id="availability-pricing">
            <div class="sub-panel">
              <!-- Refine tabs -->
              <div class="tab-content sub-content section block">
                <div role="tabpanel" class="tab-pane active" id="pricing-variants">
                  Wireframes required
                </div>
                <div role="tabpanel" class="tab-pane" id="channels">
                  <div class="row">
                    <div class="col-xs-12 col-md-10">
                      <table class="table">
                      <thead>
                        <tr>
                          <th>Channel</th>
                          <th>Visible</th>
                          <th>Publish Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Storefront</td>
                          <td>
                            <div class="checkbox">
                              <input id="storefrontVisible" type="checkbox" checked>
                              <label for="storefrontVisible"><span class="check"></span></label>
                            </div>
                          </td>
                          <td class="publish-date"><a href="#" class="btn-pop-over"><i class="fa fa-calendar-o" aria-hidden="true"></i></a></td>
                        </tr>
                        <tr>
                          <td>Facebook</td>
                          <td>
                            <div class="checkbox">
                              <input id="facebookVisible" type="checkbox">
                              <label for="facebookVisible"><span class="check"></span></label>
                            </div>
                          </td>
                          <td class="publish-date"><em>Will publish on 03/30/2017 - 9:00pm</em> <a href="#" class="btn-pop-over"><i class="fa fa-calendar-o" aria-hidden="true"></i></a></td>
                        </tr>
                        <tr>
                          <td>eBay</td>
                          <td>
                            <div class="checkbox">
                              <input id="eBayVisible" type="checkbox">
                              <label for="eBayVisible"><span class="check"></span></label>
                            </div>
                          </td>
                          <td class="publish-date">
                            <a href="#" class="btn-pop-over"><i class="fa fa-calendar-o" aria-hidden="true"></a></i>
                          </td>
                        </tr>
                        <tr>
                          <td>Amazon</td>
                          <td>
                            <div class="checkbox">
                              <input id="amazonVisible" type="checkbox">
                              <label for="amazonVisible"><span class="check"></span></label>
                            </div>
                          </td>
                          <td class="publish-date">
                            <a href="#" class="btn-pop-over"><i class="fa fa-calendar-o" aria-hidden="true"></i></a>
                            <div class="pop-over publish-date">
                              <form>
                                <label>Publish this product on:</label>
                                <div class="form-group">
                                  <div class="input-group input-group-full date" data-provide="datepicker">
                                    <span class="input-group-addon">
                                      <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </span>
                                    <label class="sr-only" for="search">Date</label>
                                    <input type="text" class="form-control">
                                  </div>
                                </div>
                                <span class="form-link">
                                  at
                                </span>
                                <div class="form-group">
                                  <div class="input-group input-group-full">
                                    <span class="input-group-addon">
                                      <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    </span>
                                    <label class="sr-only" for="search">Time</label>
                                    <select class="form-control selectpicker">
                                      <option>12:00 am</option>
                                      <option>12:30 am</option>
                                      <option>1:00 am</option>
                                      <option>1:30 am</option>
                                      <option>2:00 am</option>
                                      <option>2:30 am</option>
                                      <option>3:00 am</option>
                                      <option>Etc.</option>
                                    </select>
                                  </div>
                                </div>
                                <button type="button" class="btn btn-default">Set publish date</button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="customer-groups">
                  <div class="row">
                    <div class="col-xs-12 col-md-10">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Group</th>
                            <th>Visible</th>
                            <th>Publish Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Guests</td>
                            <td>
                              <select class="form-control selectpicker">
                                <option>Yes</option>
                                <option>No</option>
                              </select>
                            </td>
                            <td>
                              <select class="form-control selectpicker">
                                <option>Yes</option>
                                <option>No</option>
                              </select>
                            </td>
                          </tr>
                          <tr>
                            <td>Retail</td>
                            <td>
                              <select class="form-control selectpicker">
                                <option>Yes</option>
                                <option>No</option>
                              </select>
                            </td>
                            <td>
                              <select class="form-control selectpicker">
                                <option>Yes</option>
                                <option>No</option>
                              </select>
                            </td>
                          </tr>
                          <tr>
                            <td>Trade</td>
                            <td>
                              <select class="form-control selectpicker">
                                <option>Yes</option>
                                <option>No</option>
                              </select>
                            </td>
                            <td>
                              <select class="form-control selectpicker">
                                <option>Yes</option>
                                <option>No</option>
                              </select>
                            </td>
                          </tr>
                          <tr>
                            <td>Partners</td>
                            <td>
                              <select class="form-control selectpicker">
                                <option>Yes</option>
                                <option>No</option>
                              </select>
                            </td>
                            <td>
                              <select class="form-control selectpicker">
                                <option>Yes</option>
                                <option>No</option>
                              </select>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="delivery">
                  Wireframes required
                </div>
                <div role="tabpanel" class="tab-pane" id="inventory">
                  Wireframes required
                </div>
                <div role="tabpanel" class="tab-pane" id="discounts">
                  Wireframes required
                </div>
              </div>
              <ul class="nav nav-tabs secondary sub-nav" role="tablist">
                <li role="presentation" class="active"><a href="#pricing-variants" aria-controls="pricing-variants" role="tab" data-toggle="tab">Pricing &amp; Variants</a></li>
                <li role="presentation"><a href="#channels" aria-controls="channels" role="tab" data-toggle="tab">Channels</a></li>
                <li role="presentation"><a href="#customer-groups" aria-controls="customer-groups" role="tab" data-toggle="tab">Customer Groups</a></li>
                <li role="presentation"><a href="#delivery" aria-controls="delivery" role="tab" data-toggle="tab">Delivery</a></li>
                <li role="presentation"><a href="#inventory" aria-controls="inventory" role="tab" data-toggle="tab">Inventory</a></li>
                <li role="presentation"><a href="#discounts" aria-controls="discounts" role="tab" data-toggle="tab">Discounts</a></li>
              </ul>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane" id="collections">
            <div class="sub-panel">
              <div class="sub-content section block">
                Wireframes required
              </div>
              <div class="sub-nav">

              </div>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane" id="associations">
            <div class="sub-panel">
              <div class="sub-content section block">
                Wireframes required
              </div>
              <div class="sub-nav">

              </div>
            </div>
          </div>
        </div>
@endsection