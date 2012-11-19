(function($j) {
	/**
	 * Account Model
	 */
	window.Account = Backbone.Model.extend({
		initialize: function Account() {
			console.log(this.attributes);

			// Error event (Validate function)
			this.on('error', function(model, error) {
				alert(error);
			});

			// Change event on name
			this.on('change:name', function() {
				//alert(this.get('name'));
			});
		},

		/**
		 * Properties validation
		 * @param attributes (Parameter is passed automatically)
		 */
		validate: function(attributes) {
			if (attributes.name === '') {
				return "Le nom du compte doit être remplie";
			}
		}
	});

	/**
	 * Account Collection
	 */
	window.AccountCollection = Backbone.Collection.extend({
		// Model set
		model: Account,

		// Url to synchronize account with bdd
		url: "/web/app_dev.php/syncaccount/",

		initialize: function() {
			console.log('new AccountCollection()');
		}
	});

	$j(document).ready(function() {
		/**
		 * Account Collection View
		 */
		window.AccountCollectionView = Backbone.View.extend({
			// Element
			el : $('#j-accountsBody'),

			initialize: function() {
				this.template = _.template($j('#j-accountsBodyTpl').html());
				//_.bindAll(this, 'render');
				//this.collection.on('change', this.render);
				//this.collection.on('add', this.render);
				//this.collection.on('remove', this.render);
			},

			render: function() {
				var renderedContent = this.template({ accounts: this.collection.toJSON() });
				$(this.el).html(renderedContent);
				$(this.el).find('input, .j-save').hide();
				return this;
			},

			events: {
				"click .j-delete" : 'removeAccount',
				"click .j-edit" : 'editAccount',
				"click .j-save" : 'saveAccount'
			},

			removeAccount: function(e) {
				e.preventDefault();
				var idAccount = $('#' + e.currentTarget.attributes['id'].nodeValue).parents('tr').first().find('.j-id').val();
				var oAccount = this.collection.get(idAccount);

				oAccount.destroy({
					success: function(model, response, options) { 
						$('#j-rowAccount' + idAccount).fadeOut(1000);
					}
				});
			},

			editAccount: function(e) {
				//e.preventDefault();
				var bCanEdit = true;
				_.each($('.j-rowAccount'), function(elt) {
					if ($(elt).hasClass('j-onEdit'))
						bCanEdit = false;
				});

				if (bCanEdit) {
					var idAccount = $('#' + e.currentTarget.attributes['id'].nodeValue).parents('tr').first().find('.j-id').val();
					$('#j-rowAccount' + idAccount).addClass('j-onEdit');
					$('#j-rowAccount' + idAccount).find('span').hide();
					$('#j-rowAccount' + idAccount).find('input, .j-save').show();
				} else {
					alert("Vous avez déjà un compte en édition.s Merci de le sauvegarder avant d'en éditer un autre.");
				}
			},

			saveAccount: function(e) {
				e.preventDefault();
				var idAccount = $('#' + e.currentTarget.attributes['id'].nodeValue).parents('tr').first().find('.j-id').val();
				var oAccount = this.collection.get(idAccount);

				_.each($('#j-rowAccount' + idAccount).find('input'), function(elt) {
					oAccount.set($(elt).attr('name'), $(elt).val());
				});

				var _self = this;
				oAccount.save({}, {
					success: function(model, response, options) { 
						_self.render();
						$('#j-rowAccount' + idAccount).removeClass('j-onEdit');
					}
				});
			}
		});

		/**
		 * Account Router
		 */
		window.AccountRouter = Backbone.Router.extend({
			initialize: function() {
				// Création et Chargement de la collection
				var accounts = new AccountCollection();
				accounts.fetch({ success: function(accounts) {
					// Création des vues + affichage
					var accountView = new AccountCollectionView({ collection : accounts });
					accountView.render();
				}});

				// Edition d'un compte
				/*this.route("editaccount/:id", "account", function(id){
					$('#j-edit' + id).click();
				});*/
			}
		});

		// Transform PUT and DELETE request into a POST resquest
		Backbone.emulateHTTP = true;

		// Router Init
		//var oRouter = new AccountRouter();

		// Monitoring activation and url dispatch
		//Backbone.history.start()
	});




	/*var aC = new AccountCollection();
	aC.fetch({ success: function(recs) {
		var aCV = new AccountCollectionView({ collection: aC });
		aCV.render();
	}});*/


	/*
	//console.log(aC.get(1));
	//aC.get('2').destroy({ success: function(rec) { console.log('compte '+ rec.id +' supprimé'); }});

	/*
	var a1 = new Account({id:1, idGroup:2, name:'facebook', url:'http://www.facebook.com', login:'thb', password:'adlthb'});
	var a2 = new Account({id:2, idGroup:2, name:'Twitter', url:'http://twitter.com', login:'ret', password:'adlret'});
	var a4 = new Account({idGroup:2, name:'Viadeo', url:'http://viadeo.com', login:'thb', password:'adlthb'});

	var aC = new AccountCollection([a1, a2, a4]);
	//a4.save();
	a4.save();
	a2.save();

	var aC = new AccountCollection();
	aC.fetch();
	aC.destroy();
	*/

})(jQuery);

