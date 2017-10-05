var componentLoader = require('./classes/ComponentLoader');
var loader = new componentLoader();

var includes = [
  /**
   * Generic components
   */
  {
    path: 'elements',
    components: {
      'alert' : 'AlertPanel',
      'button' : 'Button',
      'modal' : 'Modal',
      'notification' : 'NotificationBar',
      'delete' : 'Delete'
    }
  },
  /**
   * Tabs
   */
  {
    path: 'elements.tabs',
    components: {
      'tab' : 'CandyTab',
      'tabs' : 'CandyTabs',
    }
  },
  /**
   * Forms
   */
  {
    path: 'elements.forms',
    components: {
      'option-translatable' : 'OptionTranslatable',
      'attribute-translatable' : 'AttributeTranslatable'
    }
  },
  {
    path: 'elements.forms.inputs',
    components: {
      'checkbox' : 'Checkbox',
      'input' : 'Input',
      'radio' : 'Radio',
      'select' : 'Select',
      'taggable' : 'Taggable',
      'textarea' : 'Textarea',
      'toggle' : 'Toggle'
    }
  },
  /**
   * Tables
   */
  {
      path: 'elements.tables',
      components: {
          'fancytree' : 'Fancytree',
          'table' : 'Table'
      }
  },
  /**
   * Products
   */
  {
    path: 'catalogue-manager.products',
    components: {
      'products-table' : 'ProductsTable',
      'product-edit' : 'ProductEdit',
      'product-create' : 'ProductCreate'
    }
  },
  {
    path: 'catalogue-manager.products.edit',
    components: {
      'product-details' : 'ProductDetails',
      'product-variants' : 'ProductVariants',
      'product-availability' : 'ProductAvailability'
    }
  },
  /**
   * Collections
   */
  {
    path: 'catalogue-manager.collections',
    components: {
      'collections-table' : 'CollectionsTable'
    }
  },
  /**
   * Categories
   */
  {
    path: 'catalogue-manager.categories',
    components: {
      'categories-list' : 'List',
      'category-edit' : 'Edit'
    }
  },
  /**
   * Pagination
   */
  {
    path: 'elements.tables',
    components: {
      'table-paginate' : 'TablePaginate'
    }
  },
  /**
   * Media
   */
  {
    path: 'catalogue-manager.products.edit.media',
    components: {
      'product-media' : 'Media'
    }
  },
  {
      path: 'catalogue-manager.categories.edit.media',
      components: {
          'category-media' : 'Media'
      }
  },
  /**
   * Availability & Pricing
   */
  {
    path: 'catalogue-manager.products.edit.availability-pricing',
    components: {
      'discounts' : 'Discounts'
    }
  },
  {
    path: 'catalogue-manager.products.edit.availability-pricing.variants',
    components: {
      'variants' : 'Variants',
      'create-variant' : 'CreateVariant',
      'edit-options' : 'EditOptions'
    }
  },
  /**
   * Associations
   */
  {
    path: 'catalogue-manager.products.edit.associations',
    components: {
      'categories' : 'Categories',
      'collections' : 'Collections',
      'products' : 'Products',
      'association-modals' : 'Modals'
    }
  },
  {
    path: 'catalogue-manager.products.edit.display',
    components: {
      'display' : 'Display'
    }
  },
  {
    path: 'catalogue-manager.products.edit.urls',
    components: {
      'locale-urls' : 'LocaleURLs',
      'redirects' : 'Redirects',
      'url-modals' : 'Modals'
    }
  },
]

includes.forEach(include => {
  var src = loader.src(include.path);
  Object.keys(include.components).map((ref, key) => {
    Vue.component('candy-' + ref, require(src + include.components[ref] + '.vue'));
  });
});