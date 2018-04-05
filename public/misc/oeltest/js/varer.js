

var App = {
		Collection : {},
		Model : {},
		View : {}
	};

App.Model.Transaction = Backbone.Model.extend({
});

App.Collection.TransactionList = Backbone.Collection.extend({

	model: App.Model.Transaction,
	url : "http://gahk.dk/nyintern/oelkaelder/purchase"


});

App.Model.Product = Backbone.Model.extend({

	defaults : {quantity : 0},
	initialize : function() {
		this.set("quantity_type","x");
    	if(this.get("current_price")=="0"){
    		this.set("quantity_type","kr");
    	}
    	if(this.get("weight_price")!="0"){
    		this.set("quantity_type","g");
    	}
  	},

	total : function() {
		if(this.get("current_price")=="0"){
			if(this.get("weight_price")!="0"){
				return Math.round((this.get("quantity")*parseFloat(this.get("weight_price"))/100)*100)/100;
			}
			//Quantity for slik er faktisk den samlede pris
			return this.get('quantity');
		}else{
			return this.get('current_price') * this.get('quantity');
		}
		
	},
	//increment the quantity
	increment: function() {
		this.set('quantity', this.get('quantity')+1 );

	}
});

App.Model.Alumne = Backbone.Model.extend({
	
	checkud : function(){
		$("#alumneliste").hide();
		$("#checkout").show();
		this.saldocheck();

	},

	distribute : function(){
		$("#andre").show();

		App.AlumneDistributions.add(this);
	},

		

	saldocheck : function(){
	//	if(this.get('saldo') <= 0){
	//		$("#nocredit").show();
			//nullALL
	//	}
	//	else{
		if(App.AlumneDistributionCollection.length>0){
			var alumnumIds = [this.get('alumnumId')];
			App.AlumneDistributionCollection.each(function(alumne){
			alumnumIds.push(alumne.get('alumnumId'));
		},this);

			checkoutFunction("Delt køb", alumnumIds);
			App.AlumneDistributionCollection.reset();
		}else{
			checkoutFunction(this.get('name'),[this.get('alumnumId')]);
		}
			


			
			
			//check ud!
			//$("#confirmation").hide();

		}
	//}

});

App.Collection.AlumneList = Backbone.Collection.extend({

	model: App.Model.Alumne,
	url : "http://gahk.dk/nyintern/oelkaelder/activeShoppers"

});

App.View.AlumneList = Backbone.View.extend({

	el: "#alumneliste-checkout",

	render: function(){
		this.$el.html('');
		_.each(this.model.models, function(alumne){
			if(!App.AlumneDistributionCollection.contains(alumne)){
				var aview = new App.View.Alumne({model:alumne});
			$(this.el).append(aview.render().el);
			}
			
		}, this);

        return this;
	}
});

App.View.Alumne = Backbone.View.extend({

	tagName: "li",
	template: _.template($('#alumne-checkout').html()),

	model: App.Model.Alumne,
	events : {
		'click .checkudsom' : 'checkud',
		'click .fordel' : 'distribute',
	},

	render: function () {
	this.$el.html(this.template(this.model.toJSON()));
	return this;
	},

	checkud: function (){
		this.model.checkud();
	},

	distribute: function(){
		this.model.distribute();
		this.$el.hide();
	}
});

App.View.DistributedAlumne = Backbone.View.extend({
	tagName: "li",
	template: _.template($("#alumne-distribution").html()),
	model: App.Model.Alumne,
	events : {
		'click .fjern' : 'remove',
	},

	remove : function(){
		App.AlumneDistributionCollection.remove(this.model);
		if(App.AlumneDistributionCollection.length>0){
			App.AlumneDistributions.render();
			App.AlumnelistView.render();
		}else{
			$("#andre").hide();
			App.AlumnelistView.render();
		}
		
	},
	render : function() {

		// Render this view and return its context
	    this.$el.html(  this.template( this.model.toJSON() ));
	    //var basketItemTemplate = this.template(this.model.toJSON());
		return this;

	},

});

App.Collection.AlumneDistributions = Backbone.Collection.extend({
	model: App.Model.Alumne,



});

App.View.AlumneDistributions = Backbone.View.extend({
	el: "#alumne-distribution-list",

	initialize : function(){

		this.collection = App.AlumneDistributionCollection;


	},
	render: function(){
		// Empty the view
		this.$el.html('');

		// Loop through the collection
		this.collection.each(function( alumne ){

			// Render each item model into this List view
			newItem = new App.View.DistributedAlumne({ model : alumne });
			this.$el.append( newItem.render().el );

		// Pass this list views context
		}, this);
	},

		add : function( item ) {


	    if(!this.collection.contains(item)){
			this.collection.add( item );

	    }
		
		// Render the view
		this.render();

	},
	removeAll(){
		this.collection.reset();
		$("#andre").hide();
	},

	checkout : function(){
		var alumnumIds = [];
		this.collection.each(function(alumne){
			alumnumIds.push(alumne.get('alumnumId'));
		},this);
		$("#checkout").show();
		checkoutFunction("Delt køb", alumnumIds);
		this.collection.reset();
	},

});

// Define our Collection
App.Collection.ProductList = Backbone.Collection.extend({

    model: App.Model.Product,

    url : "http://gahk.dk/nyintern/oelkaelder/products",
   

    subtotal : function() {
		
		var total = 0;

		this.each(function( model ){
			total += model.total();
		});
		return total;
	}


});

App.View.ProductList = Backbone.View.extend({

    el: "#product-item-list",

    render: function() {
	_.each(this.model.models, function(product){
		if(product.get("current_price")=="0"){

			var spview = new App.View.SpecialProduct({model:product});
			$(this.el).append(spview.render().el);
		}else{
			var pview = new App.View.Product({ model:product });
			$(this.el).append(pview.render().el);
		}
	   // console.log(JSON.stringify(pview.model.toJSON()))
    	}, this);

        return this;
    }
	
});
					   
App.View.SpecialProduct = Backbone.View.extend({
	template: _.template($('#specialproduct-item').html()),

        events: {
		'click #1' : function(){this.addToBasket(1);},
		'click #2' : function(){this.addToBasket(2);},
		'click #3' : function(){this.addToBasket(3);},
		'click #4' : function(){this.addToBasket(4);},
	},

    render: function () {
	this.$el.html(this.template(this.model.toJSON()))
//	console.log(this.$el.html());
	//var productTemplate = this.template(this.model.toJSON());
	return this;

    },

	addToBasket : function(no) {
		$("#basket").show();
		$("#medlem").show();

		
		// Add the model to the basket
		if(no == 1){
			this.model.set("quantity",this.model.get("quantity")+parseFloat(this.model.get("price_steps1")));
		}else if(no == 2){
			this.model.set("quantity",this.model.get("quantity")+parseFloat(this.model.get("price_steps2")));
		}
		else if(no == 3){
			this.model.set("quantity",this.model.get("quantity")+parseFloat(this.model.get("price_steps3")));
		}else{
			this.model.set("quantity",this.model.get("quantity")+parseFloat(this.model.get("price_steps4")));
		}
		App.basket.add( this.model );
	}

});

App.View.Product = Backbone.View.extend({
                    
    template: _.template($('#product-item').html()),

        events: {
		'click' : 'addToBasket'
	},

    render: function () {
	this.$el.html(this.template(this.model.toJSON()))
//	console.log(this.$el.html());
	//var productTemplate = this.template(this.model.toJSON());
	return this;

    },

	addToBasket : function() {
		$("#basket").show();
		$("#medlem").show();
		this.model.increment();
		// Add the model to the basket
		App.basket.add( this.model );
	}


    });

// Individual View for Item inside Shopping Cart
App.View.BasketItemView = Backbone.View.extend({


    template :  _.template($('#basket-item').html()),
    model: App.Model.Product,
	events : {
		'click .slet' : 'remove',
		'click .fordel' : 'fordel',
	},

	initialize : function() {
		this.render();

		// If this models contents change, we re-render
		this.model.on('change', function(){
			this.render();
		}, this);

	},

	render : function() {

		// Render this view and return its context
	    this.$el.html(  this.template( this.model.toJSON() ));
	    //var basketItemTemplate = this.template(this.model.toJSON());
		return this;

	},

	// Event for the quantity UI, pass the event

	remove : function(){

		// Fade out item out from the shopping cart list
		this.$el.fadeOut(400, function(){
                         
			// Remove it from the DOM on completetion
			$(this).remove();

		});
		
                                               
		// Remove the model for this view from the basket Items collection
		App.basketItems.remove( this.model );
         this.model.set("quantity",0);
	},

	fordel : function(){
		$("#fordel").show();
	}

});

// View for the basket
App.View.Basket = Backbone.View.extend({

	el: '#basket-list',

	// Some other elements to cache
	total : $('#total'),

	initialize : function(){

		this.collection = App.basketItems;
		// Listen for events ( add, remove or a change in quantity ) in the collection
		this.collection.on('add remove change:quantity', function( item ){

			// Update the main total based on the new data
			this.updateTotal();


		// Pass in this views context
		}, this);

	},

	add : function( item ) {


	    if(!this.collection.contains(item)){
			this.collection.add( item );

	    }
		
	    this.updateTotal();
		// Render the view
		this.render();

	},

	removeAll : function () {
		$("#basket").hide();
		$("#medlem").hide();
		this.collection.each(function(product){
                    product.set("quantity",0);
                });

		this.collection.reset();
		this.updateTotal();
		this.render();

	},

	// Update the totals in the cart
	updateTotal : function() {

		// Inject these totals
		this.total.html( "Samlet pris: " + this.collection.subtotal() + " kr" );

	},

	render : function(){

		// Empty the view
		this.$el.html('');

		// Loop through the collection
		this.collection.each(function( product ){

			// Render each item model into this List view
			newItem = new App.View.BasketItemView({ model : product });
			this.$el.append( newItem.render().el );

		// Pass this list views context
		}, this);

	}

});
//Virkelig lang kode til at genere nummer panelet, blev revet lidt med af backbone modeller og views
App.Model.Checkout = Backbone.Model.extend({
	defaults : {number : 0}
});

App.View.Checkout = Backbone.View.extend({
	template :  _.template($('#numbers').html()),

	events : {
		'click' : 'add',
	},

	render : function() {
		// Render this view and return its context
	    this.$el.html(  this.template( this.model.toJSON() ));
		return this;

	},

	add : function() {
		App.CheckoutNumberPad.addToAlumneNo( this.model );
	}
});

App.Collection.CheckoutNumbers = Backbone.Collection.extend({
	model : App.Model.Checkout
}); 

App.View.Checkouts = Backbone.View.extend({
	el: "#checkout-nos",
	checkoutnumber : $("#shopper-id"),
	alumneNo : "",

	//Code to generate numberpad
	initialize : function(){
		this.collection = new App.Collection.CheckoutNumbers;
		for(i=1; i<10; i++ ){
			var nmodel = new App.Model.Checkout();
			nmodel.set("number",i);
			this.collection.add(nmodel);
			var nview = new App.View.Checkout({model: nmodel});
			this.$el.append(nview.render().el);
		};

		$('<li class="neutral" id="sealumneliste"><img src="images/list.png" alt="se alumneliste"></li>').appendTo(this.$el);
		var nmodel = new App.Model.Checkout();
		nmodel.set("number",0);
		this.collection.add(nmodel);
		var nview = new App.View.Checkout({model: nmodel});
		this.$el.append(nview.render().el);
		$('<li class="neutral" id="undo"><img src="images/erasenumber.png" alt="slet sidst indtastede nummer"></li>').appendTo(this.$el);

	},

	nulstilAlumneNo : function(){
		this.alumneNo = "";
		this.checkoutnumber.html( this.alumneNo );
	},

	addToAlumneNo : function( item ){
		this.alumneNo = this.alumneNo.concat(item.get("number"));
		this.checkoutnumber.html( this.alumneNo );
		//SHOPPER ID CHANGE!
		if(this.alumneNo.length==3){
			this.checkShopperID();
		}

	},

	checkShopperID : function(){
		var matched = false;
		_.each(App.Alumnelist.models, function(alumne){
			if(alumne.get('alumnumId')==this.alumneNo){
				alumne.checkud();
				matched = true;
			}else{
			}
    	}, this);

    	if(!matched){
    		$("#checkout").show();
    		$("#wrongnumber").show();
    	}
    	
	},

	undo: function(){
		this.alumneNo = this.alumneNo.substring(0,this.alumneNo.length-1);
		this.checkoutnumber.html( this.alumneNo );
	}

});


$(function(){




App.products = new App.Collection.ProductList();    
App.productslistView = new App.View.ProductList({model: App.products});
App.products.fetch({
					success: function() {
						console.log("success!!");
                    	App.productslistView.render();
                    },
                    failure: function(){
                    	console.log("failure!!!");
                    }
                });

App.Alumnelist = new App.Collection.AlumneList();
App.AlumnelistView = new App.View.AlumneList({model: App.Alumnelist});
App.Alumnelist.fetch({
	success: function(){
		console.log("alumne success!!");
		App.AlumnelistView.render();
	},
	failure: function(){
		console.log("failure alumne");
	}

});

App.basketItems = new App.Collection.ProductList();

App.Transactions = new App.Collection.TransactionList();

// Example of an external listener,
// Execute when a model is added to the cart Items collection
App.CheckoutNumberPad = new App.View.Checkouts({model: new App.Collection.CheckoutNumbers()});
App.basket = new App.View.Basket();
App.AlumneDistributionCollection = new App.Collection.AlumneDistributions();
App.AlumneDistributions = new App.View.AlumneDistributions();

//some button events
$("#sealumneliste").click(function(event){
	$("#alumneliste").show();
	$("#checkout").show();
});

$("#undo").click(function(event){
	App.CheckoutNumberPad.undo();
});

$(".luk").click(function(event){
	$("#alumneliste").hide();
	$("#checkout").hide();
});

$("#okwrongnumber").click(function(event){
	App.CheckoutNumberPad.nulstilAlumneNo();
	$("#checkout").hide();
	$("#wrongnumber").hide();
});

$("#oknocredit").click(function(event){
	App.CheckoutNumberPad.nulstilAlumneNo();
	$("#checkout").hide();
	$("#nocredit").hide();
});

$("#tilbage").click(function(event){
	$("#fordel").hide();
});

$("#nulstil").click(function(event){
	App.basket.removeAll();
});

$("#nulstilAlumnums").click(function(event){
	App.AlumneDistributions.removeAll();
	App.AlumnelistView.render();
});

$("#fortryd").click(function(event){
	$("#stopTransaction").val("true");
});

$("#buttonCheckOut").click(function(event){
	App.AlumneDistributions.checkout();
});

$("#godkend").click(function(event){
	$("#confirmTransaction").val("true");
});
    var reset = 120;
var count = reset;
document.onclick = 
    function(event){
        $("clicked").val("true");
        count = reset;
    };

checkoutFunction = function(name, alumnumIds){

	$("#confirmationname").text(name);
			$("#confirmation").show();

			var x = 5;
			//var alumnumIds = this.get("alumnumId");
			var check = function(){
				//event handler bottom of page sets this value on fortryd click
				if($("#stopTransaction").val()=="true"){
					App.CheckoutNumberPad.nulstilAlumneNo();
					$("#confirmation").hide();
					$("#checkout").hide();
					$("#stopTransaction").val("");
					$("#confirmTransaction").val("");
				}
			    else if(x<0 || $("#confirmTransaction").val()=="true"){
					var transaction = new App.Model.Transaction();
					var date = new Date();
					var datestring = date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate() + " "+ date.getHours()+":"+date.getMinutes();
					transaction.set("date",datestring);
					transaction.set("shoppers",alumnumIds);
					transaction.set("items",App.basket.collection);
					console.log(JSON.stringify(transaction.toJSON()));
					App.Transactions.add(transaction);
					//Transaction.sync("create");
					Backbone.sync("create",transaction);
					$("#confirmation").hide();
					App.basket.removeAll();
					App.CheckoutNumberPad.nulstilAlumneNo();
					$("#checkout").hide();
					$("#andre").hide();
					$("#stopTransaction").val("");
					$("#confirmTransaction").val("");
			    }
			    else {
			    	$( "#fortryd" ).text("Fortryd ("+x+")");
					x = x-1;
			        setTimeout(check, 1000); // check again in a second
			    }
			}

			check();
}



var counter = function(){
	count = count-1;
	if(count==0){
		location.reload(); 
		count = reset;
	}
	setTimeout(counter, 1000); // check again in a second
}

counter();


});
