<template>
  <div class="sub-panel">
    <div class="sub-content section block">
      <div class="row">
        <div class="col-xs-12 col-md-11">
          <h4>Media</h4>
          <hr>
          <div class="btn-group">
            <button type="button" class="btn btn-primary">All Media</button>
            <button type="button" class="btn btn-default">Images</button>
            <button type="button" class="btn btn-default">Files</button>
          </div>
          <p><em>Toggle filter, custom radio inputs</em></p>
          <table class="table">
            <thead>
              <tr>
                <th></th>
                <th>Title/Alt Tag</th>
                <th>Description</th>
                <th width="230">Tags</th>
                <th>File Type</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <a href="/images/placeholder/product.jpg" class="fresco">
                    <img src="/images/placeholder/product.jpg" alt="Aquacomb">
                  </a>
                </td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control" data-role="tagsinput"></td>
                <td>.jpg</td>
                <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#removeProduct"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
              </tr>
              <tr>
                <td>
                  <a href="/images/placeholder/product.jpg" class="fresco">
                    <img src="/images/placeholder/product.jpg" alt="Aquacomb">
                  </a>
                </td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control" data-role="tagsinput"></td>
                <td>.jpg</td>
                <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#removeProduct"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
              </tr>
              <tr>
                <td>
                  <img src="/images/file-types/pdf.svg" alt="PDF">
                </td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control" data-role="tagsinput"></td>
                <td>.pdf</td>
                <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#removeProduct"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
              </tr>
              <tr>
                <td>
                  <img src="/images/file-types/xls.svg" alt="XLS">
                </td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control" data-role="tagsinput"></td>
                <td>.xls</td>
                <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#removeProduct"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
              </tr>
              <tr>
                <td>
                  <a href="/images/placeholder/product.jpg" class="fresco">
                    <img src="/images/placeholder/product.jpg" alt="Aquacomb">
                  </a>
                </td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control" data-role="tagsinput"></td>
                <td>.jpg</td>
                <td align="right"><button class="btn btn-sm btn-default btn-action" data-toggle="modal" data-target="#removeProduct"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
              </tr>
            </tbody>
          </table>

          <p>File icons sourced from Flaticon, we'd need to purchase these or mention the author if we want to use them for free.</p>
        </div>
      </div>
    </div>
    <div class="sub-nav media-upload">
      <button type="button" class="btn btn-primary btn-full">Browse existing media</button>
      <form id="id_dropzone" 
      class="dropzone" 
      action="/ajax_file_upload_handler/"
      enctype="multipart/form-data" 
      method="post">
        
        <div class="media-box">
          <i class="fa fa-upload icon" aria-hidden="true"></i>
          <p>Drop files here or click to upload</p>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        }
    }
</script>