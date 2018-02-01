<script>
    export default {
        data() {
            return {
            }
        }
    }
</script>

<template>
  <div>
    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="addCategory">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add this product to categories</h4>
          </div>
          <div class="modal-body" id="categoryList">
            <div class="form-group">
              <label class="sr-only">Search</label>
              <input type="text" class="form-control search" placeholder="Search Categories">
            </div>
            <hr>

            <table class="table association-table">
              <thead>
                <tr>
                  <th width="65"></th>
                  <th width="40%">Name</th>
                  <th width="40%">URL</th>
                  <th></th>
                </tr>
              </thead>
              <tbody class="list">
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td width="40%" class="name">Category 1</td>
                  <td width="40%"><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="catSelect1" type="checkbox">
                      <label for="catSelect1"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td width="40%" class="name">Category 2</td>
                  <td width="40%"><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="catSelect2" type="checkbox">
                      <label for="catSelect2"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td width="40%" class="name">Category 3</td>
                  <td width="40%"><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="catSelect3" type="checkbox">
                      <label for="catSelect3"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td width="40%" class="name">Category 4</td>
                  <td width="40%"><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="catSelect4" type="checkbox">
                      <label for="catSelect4"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td width="40%" class="name">Category 2</td>
                  <td width="40%"><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="catSelect5" type="checkbox">
                      <label for="catSelect5"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td width="40%" class="name">Category 3</td>
                  <td width="40%"><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="catSelect6" type="checkbox">
                      <label for="catSelect6"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td width="40%" class="name">Category 4</td>
                  <td width="40%"><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="catSelect7" type="checkbox">
                      <label for="catSelect7"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td width="40%" class="name">Category 4</td>
                  <td width="40%"><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="catSelect8" type="checkbox">
                      <label for="catSelect8"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td width="40%" class="name">Category 4</td>
                  <td width="40%"><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="catSelect9" type="checkbox">
                      <label for="catSelect9"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td width="40%" class="name">Category 4</td>
                  <td width="40%"><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="catSelect10" type="checkbox">
                      <label for="catSelect10"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

            <hr>
            <p><small>Need to work on scrollable tbody.<br>Should selected category names show below search area as well? With a potential to remove?</small></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary">Add to Categories</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Remove Category Modal -->
    <div class="modal fade" id="removeCategory" tabindex="-1" role="dialog" aria-labelledby="removeCategory">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Are you wish to remove this category?</h4>
          </div>
          <div class="modal-body">
            <table class="table">
              <tr>
                <td>Category</td>
                <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary">Confirm Removal</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Collection Modal -->
    <div class="modal fade" id="addCollection" tabindex="-1" role="dialog" aria-labelledby="addCollection">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add this product to collections</h4>
          </div>
          <div class="modal-body" id="collectionList">
            <div class="form-group">
              <label class="sr-only">Search</label>
              <input type="text" class="form-control search" placeholder="Search Collections">
            </div>
            <hr>
            <table class="table association-table">
              <thead>
                <tr>
                  <th width="50"></th>
                  <th>Name</th>
                  <th>URL</th>
                  <th></th>
                </tr>
              </thead>
              <tbody class="list">
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">Collection 1</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="colSelect1" type="checkbox">
                      <label for="colSelect1"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">Collection 2</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="colSelect2" type="checkbox">
                      <label for="colSelect2"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">Collection 3</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="colSelect3" type="checkbox">
                      <label for="colSelect3"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">Collection 4</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="colSelect4" type="checkbox">
                      <label for="colSelect4"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">Collection 3</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="colSelect5" type="checkbox">
                      <label for="colSelect5"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">Collection 4</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="colSelect6" type="checkbox">
                      <label for="colSelect6"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">Collection 3</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="colSelect7" type="checkbox">
                      <label for="colSelect7"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">Collection 4</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="colSelect8" type="checkbox">
                      <label for="colSelect8"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">Collection 3</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="colSelect9" type="checkbox">
                      <label for="colSelect9"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">Collection 4</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="colSelect10" type="checkbox">
                      <label for="colSelect10"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <hr>
            <p><small>Need to work on scrollable tbody.<br>Should selected category names show below search area as well? With a potential to remove?</small></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary">Add to Collections</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Remove Collection Modal -->
    <div class="modal fade" id="removeCollection" tabindex="-1" role="dialog" aria-labelledby="removeCollection">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Are you wish to remove this collection?</h4>
          </div>
          <div class="modal-body">
            <table class="table">
              <tr>
                <td>Collection</td>
                <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary">Confirm Removal</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="addProduct">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add product associations</h4>
          </div>
          <div class="modal-body" id="productList">
            <div class="form-group">
              <label class="sr-only">Search</label>
              <input type="text" class="form-control search" placeholder="Search Products">
            </div>
            <hr>
            <p><em>Work in progress, need to think about how to select as Upsell, Cross-sell and Alternate Products</em></p>
            <div class="custom-radio-group">
              <span class="group-label">Selection Type:</span>
              <div class="toggle-radio">
                <input type="radio" name="products" id="selectUpsell" value="selectUpsell" checked="checked">
                <label for="selectUpsell">
                  <span class="check"></span>
                  <span class="faux-label">Upsell</span>
                </label>
              </div>
              <div class="toggle-radio">
                <input type="radio" name="products" id="selectCrossSell" value="selectCrossSell">
                <label for="selectCrossSell">
                  <span class="check"></span>
                  <span class="faux-label">Cross-sell</span>
                </label>
              </div>
              <div class="toggle-radio">
                <input type="radio" name="products" id="selectAlternate" value="selectAlternate">
                <label for="selectAlternate">
                  <span class="check"></span>
                  <span class="faux-label">Alternate</span>
                </label>
              </div>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th> </th>
                  <th>Name</th>
                  <th>SKU</th>
                  <th>URL</th>
                  <th>Categories</th>
                  <th>Collections</th>
                  <th></th>
                </tr>
              </thead>
              <tbody class="list">
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">Product 1</td>
                  <td class="sku">SKU0000001</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td class="category">Cat 1, Cat 2</td>
                  <td class="collection">Col A, Col B</td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="prodSelect1" type="checkbox">
                      <label for="prodSelect1"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">Product 2</td>
                  <td class="sku">SKU0000002</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td class="category">Cat 3, Cat 4</td>
                  <td class="collection"></td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="prodSelect2" type="checkbox">
                      <label for="prodSelect2"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">Product 3</td>
                  <td class="sku">SKU0000003</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td class="category">Cat 1, Cat 6</td>
                  <td class="collection">Col B</td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="prodSelect3" type="checkbox">
                      <label for="prodSelect3"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width="50"><img src="/images/placeholder/product.jpg" alt="Aquacomb" class="img-sm"></td>
                  <td class="name">Product 4</td>
                  <td class="sku">SKU0000004</td>
                  <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                  <td class="category">Cat 5, Cat 2</td>
                  <td class="collection">Col A</td>
                  <td align="right">
                    <div class="checkbox">
                      <input id="prodSelect4" type="checkbox">
                      <label for="prodSelect4"><span class="check"></span></label>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <hr>
            <p><small>Need to make tbody scrollable with a fixed height<br>Should selected product names with selection type show below search area as well? With a potential to remove?</small></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary">Add to Product</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Remove Product Modal -->
    <div class="modal fade" id="removeProduct" tabindex="-1" role="dialog" aria-labelledby="removeProduct">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Are you wish to remove this product?</h4>
          </div>
          <div class="modal-body">
            <table class="table">
              <tr>
                <td>Product</td>
                <td><input type="text" class="form-control" value="/aquacomb/" disabled></td>
                <td><span class="label label-primary">Upsell</span></td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary">Confirm Removal</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
