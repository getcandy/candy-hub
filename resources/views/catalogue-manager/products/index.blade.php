@extends('getcandy_cms::layout')

@section('side_menu')
    @include('getcandy_cms::catalogue-manager.partials.side-menu')
@endsection

@section('header_title')
    <small>Catalogue Manager</small>
    <h1>Products</h1>
@endsection

@section('header_actions')
    <button class="btn btn-default white">Export</button>
    <button class="btn btn-default white">Import</button>
    <button class="btn btn-success"><i class="fa fa-plus fa-first" aria-hidden="true"></i> Add Product</button>
@endsection

@section('content')


        <products-table></products-table>

{{--
        <!-- Search tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#all-products" aria-controls="all-products" role="tab" data-toggle="tab">All Products</a></li>
          <li role="presentation"><a href="#shoes" aria-controls="shoes" role="tab" data-toggle="tab">Shoes <i class="fa fa-times" aria-hidden="true"></i></a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content section block">
          <div role="tabpanel" class="tab-pane active" id="all-products">
            <form>
              <div class="row">
                <div class="col-xs-12 col-md-2">
                  <button type="button" class="btn btn-default btn-full btn-pop-over">Add Filter <i class="fa fa-angle-down fa-last" aria-hidden="true"></i></button>

                  <!-- Filter Pop Over -->
                  <div class="pop-over">
                    <form>
                      <label>Show all products where:</label>
                      <div class="form-group">
                        <select class="form-control selectpicker">
                          <option>Display</option>
                        </select>
                      </div>
                      <span class="form-link">
                        is
                      </span>
                      <div class="form-group">
                        <select class="form-control selectpicker">
                          <option>Visible on Storefront</option>
                        </select>
                      </div>
                      <button type="button" class="btn btn-default">Add filter</button>
                    </form>
                  </div>

                </div>
                <div class="form-group col-xs-12 col-md-8">
                  <div class="input-group input-group-full">
                    <span class="input-group-addon">
                      <i class="fa fa-search" aria-hidden="true"></i>
                    </span>
                    <label class="sr-only" for="search">Search</label>
                    <input type="text" class="form-control" id="search" placeholder="Search">
                  </div>
                </div>
                <div class="form-group col-xs-12 col-md-2">
                  <button type="submit" class="btn btn-default btn-full"><i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> Save Search</button>
                </div>
              </div>
            </form>
            <div class="filters">
              <div class="filter active">Visible on Storefront <button class="delete"><i class="fa fa-times" aria-hidden="true"></i></button></div> <div class="filter active">Visible on Facebook <button class="delete"><i class="fa fa-times" aria-hidden="true"></i></button></div>
            </div>
            <hr>
            <table class="table table-striped product-table">
              <thead>
                <tr>
                  <th>
                    <div class="checkbox bulk-options">
                      <input id="prodSelect1" type="checkbox" class="select-all">
                      <label for="prodSelect1"><span class="check"></span></label>
                      <i class="fa fa-caret-down" aria-hidden="true"></i>

                      <div class="bulk-actions">
                        <div class="border-inner">
                          1 product selected
                          <a href="#" class="btn btn-outline btn-sm">Edit</a>
                          <a href="#" class="btn btn-outline btn-sm">Publish</a>
                          <a href="#" class="btn btn-outline btn-sm">Hide</a>
                          <a href="#" class="btn btn-outline btn-sm">Delete</a>
                        </div>
                        <div class="all-selected">
                          <em>All products one this page are selected</em>
                        </div>
                      </div>
                    </div>
                  </th>
                  <th>Image</th>
                  <th>Product</th>
                  <th>Display</th>
                  <th>Purchaseable</th>
                  <th>Group</th>
                </tr>
              </thead>

              <tbody>
                <tr class="clickable">
                  <td>
                    <div class="checkbox">
                      <input id="prod1" type="checkbox">
                      <label for="prod1"><span class="check"></span></label>
                    </div>
                  </td>
                  <td class="link" data-href="{{ route('cms_cm_products_edit') }}"><img src="/vendor/getcandy_cms/images/placeholder/product.jpg" alt="Aquacomb"></td>
                  <td class="link product" data-href="{{ route('cms_cm_products_edit') }}">Aquacomb</td>
                  <td class="link" data-href="{{ route('cms_cm_products_edit') }}">All</td>
                  <td class="link" data-href="{{ route('cms_cm_products_edit') }}">All</td>
                  <td class="link" data-href="{{ route('cms_cm_products_edit') }}">Spa Care</td>
                </tr>
                <tr class="clickable" data-href="product.html">
                  <td>
                    <div class="checkbox">
                      <input id="prod2" type="checkbox">
                      <label for="prod2"><span class="check"></span></label>
                    </div>
                  </td>
                  <td class="link" data-href="product.html"><img src="/vendor/getcandy_cms/images/placeholder/product.jpg" alt="Aquacomb"></td>
                  <td class="link product" data-href="product.html">Aquacomb</td>
                  <td class="link" data-href="product.html">All</td>
                  <td class="link" data-href="product.html">All</td>
                  <td class="link" data-href="product.html">Spa Care</td>
                </tr>
                <tr class="clickable" data-href="product.html">
                  <td>
                    <div class="checkbox">
                      <input id="prod3" type="checkbox">
                      <label for="prod3"><span class="check"></span></label>
                    </div>
                  </td>
                  <td class="link" data-href="product.html"><img src="/vendor/getcandy_cms/images/placeholder/product.jpg" alt="Aquacomb"></td>
                  <td class="link product" data-href="product.html">Aquacomb</td>
                  <td class="link" data-href="product.html">All</td>
                  <td class="link" data-href="product.html">All</td>
                  <td class="link" data-href="product.html">Spa Care</td>
                </tr>
                <tr class="clickable" data-href="product.html">
                  <td>
                    <div class="checkbox">
                      <input id="prod4" type="checkbox">
                      <label for="prod4"><span class="check"></span></label>
                    </div>
                  </td>
                  <td class="link" data-href="product.html"><img src="/vendor/getcandy_cms/images/placeholder/product.jpg" alt="Aquacomb"></td>
                  <td class="link product" data-href="product.html">Aquacomb</td>
                  <td class="link" data-href="product.html">All</td>
                  <td class="link" data-href="product.html">All</td>
                  <td class="link" data-href="product.html">Spa Care</td>
                </tr>
                <tr class="clickable" data-href="product.html">
                  <td>
                    <div class="checkbox">
                      <input id="prod5" type="checkbox">
                      <label for="prod5"><span class="check"></span></label>
                    </div>
                  </td>
                  <td class="link" data-href="product.html"><img src="/vendor/getcandy_cms/images/placeholder/product.jpg" alt="Aquacomb"></td>
                  <td class="link product" data-href="product.html">Aquacomb</td>
                  <td class="link" data-href="product.html">All</td>
                  <td class="link" data-href="product.html">All</td>
                  <td class="link" data-href="product.html">Spa Care</td>
                </tr>
                <tr class="clickable" data-href="product.html">
                  <td>
                    <div class="checkbox">
                      <input id="prod6" type="checkbox">
                      <label for="prod6"><span class="check"></span></label>
                    </div>
                  </td>
                  <td class="link" data-href="product.html"><img src="/vendor/getcandy_cms/images/placeholder/product.jpg" alt="Aquacomb"></td>
                  <td class="link product" data-href="product.html">Aquacomb</td>
                  <td class="link" data-href="product.html">All</td>
                  <td class="link" data-href="product.html">All</td>
                  <td class="link" data-href="product.html">Spa Care</td>
                </tr>
                <tr class="clickable" data-href="product.html">
                  <td>
                    <div class="checkbox">
                      <input id="prod7" type="checkbox">
                      <label for="prod7"><span class="check"></span></label>
                    </div>
                  </td>
                  <td class="link" data-href="product.html"><img src="/vendor/getcandy_cms/images/placeholder/product.jpg" alt="Aquacomb"></td>
                  <td class="link product" data-href="product.html">Aquacomb</td>
                  <td class="link" data-href="product.html">All</td>
                  <td class="link" data-href="product.html">All</td>
                  <td class="link" data-href="product.html">Spa Care</td>
                </tr>
                <tr class="clickable">
                  <td>
                    <div class="checkbox">
                      <input id="prod8" type="checkbox">
                      <label for="prod8"><span class="check"></span></label>
                    </div>
                  </td>
                  <td class="link" data-href="product.html"><img src="/vendor/getcandy_cms/images/placeholder/product.jpg" alt="Aquacomb"></td>
                  <td class="link product" data-href="product.html">Aquacomb</td>
                  <td class="link" data-href="product.html">All</td>
                  <td class="link" data-href="product.html">All</td>
                  <td class="link" data-href="product.html">Spa Care</td>
                </tr>
              </tbody>
            </table>

            <div class="text-center">
              <nav aria-label="Page navigation">
                <ul class="pagination">
                  <li>
                    <a href="#" aria-label="First page" data-toggle="tooltip" data-placement="top" title="First page">
                      <span aria-hidden="true"><i class="fa fa-angle-double-left" aria-hidden="true"></i></span>
                    </a>
                  </li>
                  <li>
                    <a href="#" aria-label="Previous">
                      <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                    </a>
                  </li>
                  <li><a href="#">1</a></li>
                  <li><a href="#">2</a></li>
                  <li><a href="#">3</a></li>
                  <li><a href="#">4</a></li>
                  <li><a href="#">5</a></li>
                  <li>
                    <a href="#" aria-label="Next">
                      <span aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                    </a>
                  </li>
                  <li>
                    <a href="#" aria-label="Last page" data-toggle="tooltip" data-placement="top" title="Last page">
                      <span aria-hidden="true"><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                    </a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane" id="shoes">
            <form>
              <div class="row">
                <div class="col-xs-12 col-md-2">
                  <button type="button" class="btn btn-default btn-full btn-pop-over">Add Filter <i class="fa fa-angle-down fa-last" aria-hidden="true"></i></button>

                  <!-- Filter Pop Over -->
                  <div class="pop-over">
                    <form>
                      <label>Show all products where:</label>
                      <div class="form-group">
                        <select class="form-control selectpicker">
                          <option>Display</option>
                        </select>
                      </div>
                      <span class="">
                        is
                      </span>
                      <div class="form-group">
                        <select class="form-control selectpicker">
                          <option>Visible on Storefront</option>
                        </select>
                      </div>
                      <button type="button" class="btn btn-default">Add filter</button>
                    </form>
                  </div>

                </div>
                <div class="form-group col-xs-12 col-md-8">
                  <div class="input-group input-group-full">
                    <span class="input-group-addon">
                      <i class="fa fa-search" aria-hidden="true"></i>
                    </span>
                    <label class="sr-only" for="search">Search</label>
                    <input type="text" class="form-control" id="search" placeholder="Search">
                  </div>
                </div>
                <div class="form-group col-xs-12 col-md-2">
                  <button type="submit" class="btn btn-default btn-full"><i class="fa fa-floppy-o fa-first" aria-hidden="true"></i> Save Search</button>
                </div>
              </div>
            </form>
            <div class="filters">
              <div class="filter active">Search: Shoes <button class="delete"><i class="fa fa-times" aria-hidden="true"></i></button></div>
            </div>
            <hr>
            <table class="table table-striped product-table">
              <thead>
                <tr>
                  <th>
                    <div class="checkbox bulk-options">
                      <input id="prodSelect1a" type="checkbox" class="select-all">
                      <label for="prodSelect1a"><span class="check"></span></label>
                      <i class="fa fa-caret-down" aria-hidden="true"></i>

                      <div class="bulk-actions">
                        <div class="border-inner">
                          1 product selected
                          <a href="#" class="btn btn-outline btn-sm">Edit</a>
                          <a href="#" class="btn btn-outline btn-sm">Publish</a>
                          <a href="#" class="btn btn-outline btn-sm">Hide</a>
                          <a href="#" class="btn btn-outline btn-sm">Delete</a>
                        </div>
                        <div class="all-selected">
                          <em>All products one this page are selected</em>
                        </div>
                      </div>
                    </div>
                  </th>
                  <th>Image</th>
                  <th>Product</th>
                  <th>Display</th>
                  <th>Purchaseable</th>
                  <th>Group</th>
                </tr>
              </thead>

              <tbody>
                <tr class="clickable" data-href="product.html">
                  <td>
                    <div class="checkbox">
                      <input id="shoe_prod8" type="checkbox">
                      <label for="shoe_prod8"><span class="check"></span></label>
                    </div>
                  </td>
                  <td><img src="/vendor/getcandy_cms/images/placeholder/product.jpg" alt="Aquacomb"></td>
                  <td class="product">Aquacomb</td>
                  <td>All</td>
                  <td>All</td>
                  <td>Spa Care</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

--}}

@endsection