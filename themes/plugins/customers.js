
(function( window )
{
  // CONTROLLER
  // --------------------------------

  var Customers = 
  {
      _tplStructure:   'half'
    , _viewsNames:     [ 'Subbly.View.Customers', 'Subbly.View.CustomerSheet' ]
    , _controllerName: 'customers'
    , _mainNavRegister:
      {
          name:       'Customers'
        , order:      180
        , defaultUrl: 'customers'
      }

    , routes: {
          'customers':      'list'
        , 'customers/:uid': 'details'
      }

    // Local method
    , getCollection: function()
      {
        if( !this.collection )
          this.collection = Subbly.api('Subbly.Collection.Users')
      }

      // Routes
      //-------------------------------

    , list: function() 
      {
        this.displayView()
      }

    , details: function( uid ) 
      {
        this.displayView()
      }

    , displayView: function()
      {
        this._mainRouter._currentView = this

        this.getCollection()

        Subbly.fetch( this.collection,
        {
            data:   {
                offset: 0
              , limit:  5
            }
          , success: _.bind( this.cbAlaCon, this )
        }, this )
      }

      // test Callback
    , cbAlaCon: function( collection, response )
      {
        this.getViewByPath( 'Subbly.View.Customers' )
          .setValue( 'collection', collection )
          .displayTpl()
      }

    , sheet: function(  uid ) 
      {
        var scope = this
          , user  = Subbly.api('Subbly.Model.User', {
          uid: uid
        })

        var $listRows  = this.getViewByPath( 'Subbly.View.Customers' ).getListRows()
          , $activeRow = $listRows.filter('[data-uid="' + uid + '"]')

        $listRows.removeClass('active')
        $activeRow.addClass('active')

        Subbly.fetch( user,
        {
            data: { includes: ['addresses', 'orders'] }
          , success: function( model, response )
            {
              var json = model.toJSON()

              json.displayName = model.displayName()

              scope.getViewByPath( 'Subbly.View.CustomerSheet' )
                .setValue( 'model', model )
                .displayTpl( json )
            }
        }, this )
      }
  }


  // VIEWS
  // --------------------------------


  // List's row view (optional)
  // use it if you need to have 
  // full control on model (delete, etc.)
  // var CustomersRow = 
  // {
  //     className: 'cln-lst-rw cust-row js-trigger-goto'
  //   , _viewName: 'CustomerRow'

  //   , onInitialize: function( options )
  //     {
  //       this.tplRow = options.tpl
  //     }

  //   , render: function()
  //     {
  //       var html = this.tplRow({
  //           displayName: this.model.displayName()
  //         , createdDate: moment.utc( this.model.get('created_at') ).fromNow()
  //       })

  //       this.$el.html( html )

  //       this.el.dataset.uid  = this.model.get('uid')

  //       return this
  //     }

  //   , goTo: function( event )
  //     {
  //       this.callController( 'sheet', this.model.get('uid') )
  //     }
  // }

  // Customers List view
  var CustomersList = 
  {
      _viewName:     'Customers'
    , _viewTpl:      TPL.customers.list
    , _classlist:    ['view-half-list']
    , _listSelector: '#customers-list'
    , _tplRow:        TPL.customers.listrow
    // , _viewRow:       'Subbly.View.CustomerRow'

    , onInitialize: function()
      {
        this.addEvents( {'click li.js-trigger-goto':  'goTo'} )
      }

    , onInitialRender: function()
      {
        var $firstRow = this.getListEl().children(':first')
          , uid       = $firstRow.attr('data-uid')

        this.callController( 'sheet', uid )
      }

    , displayRow: function( model )
      {
        var html = this._tplRowCompiled({
            displayName: model.displayName()
          , createdDate: moment.utc( model.get('created_at') ).fromNow()
          , uid:         model.get('uid')
        })

        return html
      }

    , goTo: function( event )
      {
        this.callController( 'sheet', event.currentTarget.dataset.uid )
      }
  }

  // Customers Sheet view
  var CustomerSheet = 
  {
      _viewName:     'CustomerSheet'
    , _viewTpl:      TPL.customers.sheet
  }


  // REGISTER PLUGIN
  // --------------------------------


  Subbly.register( 'Subbly', 'Customers', 
  {
      'ViewList:Customers':        CustomersList
    // , 'ViewListRow:CustomerRow':   CustomersRow
    , 'View:CustomerSheet':        CustomerSheet
    , 'Controller:Customers':      Customers
  })

})( window )
