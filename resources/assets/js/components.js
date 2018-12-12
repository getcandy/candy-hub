var componentLoader = require('./classes/ComponentLoader');
var loader = new componentLoader();

var includes = [
  /**
   * Generic components
   */
  {
    path: 'elements',
    components: {
      'disabled': 'Disabled',
      'alert' : 'AlertPanel',
      'thumbnail-loader' : 'ThumbnailLoader',
      'media' : 'Media',
      'button' : 'Button',
      'modal' : 'Modal',
      'notification' : 'NotificationBar',
      'delete' : 'Delete',
      'layout-picker': 'LayoutPicker',
      'product-browser': 'ProductBrowser',
      'collection-browser': 'CollectionBrowser',
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
      'date-picker': 'DatePicker',
      'input' : 'Input',
      'number' : 'Number',
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
   * Catalogue Manager
   */
  {
    path: 'catalogue-manager.elements',
    components: {
      'customer-groups' : 'CustomerGroups',
      'channel-association' : 'Channels',
      'urls' : 'Urls',
      'redirects' : 'Redirects'
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
    path: 'marketing.discounts',
    components: {
      'discounts-table' : 'DiscountsTable',
      'edit-discount': 'EditDiscount',
      'create-discount': 'CreateDiscount'
    }
  },
  {
    path: 'marketing.discounts.edit',
    components: {
      'discount-details': 'DiscountDetails',
      'discount-availability': 'DiscountAvailability',
      'discount-conditions' : 'DiscountConditions',
      'discount-rewards' : 'DiscountRewards'
    }
  },
  {
    path: 'marketing.discounts.types',
    components: {
      'discounts-coupon': 'Coupon',
      'discounts-customer-groups' : 'CustomerGroups',
      'discounts-once-per-customer' : 'OncePerCustomer',
      'discounts-products': 'Products',
      'discounts-users': 'Users'
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
      'collections-table' : 'CollectionsTable',
      'collection-edit' : 'CollectionEdit',
      'collection-create' : 'CollectionCreate'
    }
  },
  {
    path: 'catalogue-manager.collections.edit',
    components: {
      'collection-details' : 'CollectionDetails',
      'collection-availability' : 'CollectionAvailability',
      'collection-products' : 'CollectionProducts'
    }
  },
  /**
   * Categories
   */
  {
    path: 'catalogue-manager.categories',
    components: {
      'categories-list' : 'List',
      'category-edit' : 'Edit',
      'category-create' : 'CategoryCreate'
    }
  },
  {
    path: 'catalogue-manager.categories.edit',
    components: {
      'category-availability' : 'CategoryAvailability',
      'category-details'      : 'CategoryDetails',
      'category-product-positioning' : 'ProductPositioning',
      'category-nodes' : 'CategoryNodes',
      'category-display': 'CategoryDisplay'
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
      'product-collections' : 'Collections',
      'products' : 'Products'
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
    }
  },
  /**
   * Order Processing
   */
  {
    path: 'order-processing.orders',
    components: {
      'orders-table' : 'OrdersTable',
      'order-edit' : 'OrderEdit'
    }
  },
  {
    path: 'order-processing.customers',
    components: {
      'customer-impersonate': 'Impersonate',
      'customer-create': 'CreateCustomer',
      'customers-table': 'CustomersTable',
      'customer-edit': 'CustomerEdit'
    }
  },
  {
    path: 'order-processing.shipping.methods',
    components: {
      'shipping-methods-table': 'ShippingTable',
      'shipping-method-edit': 'ShippingEdit',
      'shipping-method-create': 'ShippingCreate'
    }
  },
  {
    path: 'order-processing.shipping.zones',
    components: {
      'shipping-zones-table': 'ShippingZonesTable',
      'shipping-zone-edit': 'ShippingZoneEdit',
      'shipping-zone-create': 'ShippingZoneCreate'
    }
  },
  {
    path: 'order-processing.shipping.methods.edit',
    components: {
      'shipping-details': 'ShippingDetails',
      'add-shipping-price': 'AddShippingPrice',
      'shipping-prices': 'ShippingPrices',
      'shipping-method-zones': 'ShippingZones',
    }
  }
]

includes.forEach(include => {
  var src = loader.src(include.path);
  Object.keys(include.components).map((ref, key) => {
    Vue.component('candy-' + ref, require(src + include.components[ref] + '.vue'));
  });
});