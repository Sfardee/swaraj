/**
 * @file
 * Customization on contributor modules.
 */

(function ($, _, Backbone, Drupal) {

  'use strict';

  Backbone.on('PanelsIPEInitialized', function() {
    Drupal.panels_ipe.app_view.listenTo(Drupal.panels_ipe.app_view.model, 'styleBlock', Drupal.panels_ipe.BlockView.styleBlock);

    Drupal.panels_ipe.app_view.tabsView.tabViews['save'].revertTab.set({hidden: true});
    Drupal.panels_ipe.app_view.listenTo(Drupal.panels_ipe.app_view.tabsView.tabViews['save'].revertTab, 'change:active', function () {
      Drupal.panels_ipe.app_view.tabsView.tabViews['save'].revertTab.set({hidden: true});
    });
  });

  Drupal.panelizer.panels_ipe.SaveTabView = Backbone.View.extend(/** @lends Drupal.panelizer.panels_ipe.SaveTabView# */{

    /**
     * @type {function}
     */
    template: _.template(
      '<div class="panelizer-ipe-save-button"><a class="panelizer-ipe-save-custom" href="#">Save as custom</a></div>'
    ),

    /**
     * @type {Drupal.panels_ipe.AppModel}
     */
    model: null,

    /**
     * @type {Drupal.panels_ipe.TabsView}
     */
    tabsView: null,

    /**
     * @type {Drupal.panels_ipe.TabModel}
     */
    revertTab: null,

    /**
     * @type {object}
     */
    events: {
      'click .panelizer-ipe-save-custom': 'saveCustom',
      'click .panelizer-ipe-save-default': 'saveDefault'
    },

    /**
     * @type {function}
     */
    onClick: function () {
      this._save('panelizer_field');
      /*var entity = drupalSettings.panelizer.entity;
      if (this.model.get('saveTab').get('active')) {
        // If only one option is available, then just do that directly.
        if (!entity.panelizer_default_storage_id) {
          this._save('panelizer_field');
        }
        else if (!entity.panelizer_field_storage_id) {
          this._save('panelizer_default');
        }
      }*/
    },

    /**
     * @type {function}
     */
    saveCustom: function () {
      this._save('panelizer_field');
    },

    /**
     * @type {function}
     */
    saveDefault: function () {
      this._save('panelizer_default');
    },

    /**
     * @type {function}
     */
    _save: function (storage_type) {
      var self = this,
          layout = this.model.get('layout');

      // Give the backend enough information to save in the correct way.
      layout.set('panelizer_save_as', storage_type);
      layout.set('panelizer_entity', drupalSettings.panelizer.entity);

      if (this.model.get('saveTab').get('active')) {
        // Save the Layout and disable the tab.
        this.model.get('saveTab').set({loading: true, active: false});
        this.tabsView.render();
        layout.save().done(function () {
          self.model.get('saveTab').set({loading: false});
          self.model.set('unsaved', false);

          // Change the storage type and id for the next save.
          drupalSettings.panels_ipe.panels_display.storage_type = storage_type;
          drupalSettings.panels_ipe.panels_display.storage_id = drupalSettings.panelizer.entity[storage_type + '_storage_id'];

          // Show/hide the revert to default tab.
          //self.revertTab.set({hidden: storage_type === 'panelizer_default'});
          self.revertTab.set({hidden: true});
          self.tabsView.render();
        });
      }
    },

    /**
     * @constructs
     *
     * @augments Backbone.View
     *
     * @param {Object} options
     *   An object containing the following keys:
     * @param {Drupal.panels_ipe.AppModel} options.model
     *   The app state model.
     * @param {Drupal.panels_ipe.TabsView} options.tabsView
     *   The app view.
     * @param {Drupal.panels_ipe.TabModel} options.revertTab
     *   The revert tab.
     */
    initialize: function (options) {
      this.model = options.model;
      this.tabsView = options.tabsView;
      this.revertTab = options.revertTab;

      this.listenTo(this.model.get('saveTab'), 'change:active', this.onClick);
    },

    /**
     * Renders the selection menu for picking Layouts.
     *
     * @return {Drupal.panelizer.panels_ipe.SaveTabView}
     *   Return this, for chaining.
     */
    render: function () {
      this.$el.html(this.template());
      return this;
    }

  });

  Drupal.panels_ipe.BlockView = Backbone.View.extend(/** @lends Drupal.panels_ipe.BlockView# */{

    /**
     * @type {function}
     */
    template_actions: _.template(
      '<div class="ipe-actions-block ipe-actions" data-block-action-id="<%- uuid %>" data-block-edit-id="<%- id %>">' +
      '  <h5>' + Drupal.t('Block: <%- label %>') + '</h5>' +
      '  <ul class="ipe-action-list">' +
      '    <li data-action-id="remove">' +
      '      <a><span class="ipe-icon ipe-icon-remove"></span></a>' +
      '    </li>' +
      '    <li data-action-id="up">' +
      '      <a><span class="ipe-icon ipe-icon-up"></span></a>' +
      '    </li>' +
      '    <li data-action-id="down">' +
      '      <a><span class="ipe-icon ipe-icon-down"></span></a>' +
      '    </li>' +
      '    <li data-action-id="move">' +
      '      <select><option>' + Drupal.t('Move') + '</option></select>' +
      '    </li>' +
      '    <li data-action-id="style" title="Style">' +
      '      <a><span class="ipe-icon ipe-icon-change_layout"></span></a>' +
      '    </li>' +
      '    <li data-action-id="configure">' +
      '      <a><span class="ipe-icon ipe-icon-configure"></span></a>' +
      '    </li>' +
      '<% if (plugin_id === "block_content" && edit_access) { %>' +
      '    <li data-action-id="edit-content-block">' +
      '      <a><span class="ipe-icon ipe-icon-edit"></span></a>' +
      '    </li>' +
      '<% } %>' +
      '  </ul>' +
      '</div>'
    ),

    /**
     * @type {Drupal.panels_ipe.BlockModel}
     */
    model: null,

    /**
     * @type {object}
     */
    events: {
      'click [data-action-id="style"]': 'styleBlock'
    },

    /**
     * @constructs
     *
     * @augments Backbone.View
     *
     * @param {object} options
     *   An object with the following keys:
     * @param {Drupal.panels_ipe.BlockModel} options.model
     *   The block state model.
     * @param {string} options.el
     *   An optional selector if an existing element is already on screen.
     */
    initialize: function (options) {
      this.model = options.model;
      // An element already exists and our HTML properly isn't set.
      // This only occurs on initial page load for performance reasons.
      if (options.el && !this.model.get('html')) {
        this.model.set({html: this.$el.prop('outerHTML')});
      }
      this.listenTo(this.model, 'sync', this.finishedSync);
      this.listenTo(this.model, 'change:syncing', this.render);
    },

    /**
     * Renders the wrapping elements and refreshes a block model.
     *
     * @return {Drupal.panels_ipe.BlockView}
     *   Return this, for chaining.
     */
    render: function () {
      // Replace our current HTML.
      this.$el.replaceWith(this.model.get('html'));
      this.setElement("[data-block-id='" + this.model.get('uuid') + "']");

      // We modify our content if the IPE is active.
      if (this.model.get('active')) {
        // Prepend the ipe-actions header.
        var template_vars = this.model.toJSON();
        template_vars['edit_access'] = drupalSettings.panels_ipe.user_permission.create_content;
        this.$el.prepend(this.template_actions(template_vars));

        // Add an active class.
        this.$el.addClass('active');

        // Make ourselves draggable.
        this.$el.draggable({
          scroll: true,
          scrollSpeed: 20,
          // Maintain our original width when dragging.
          helper: function (e) {
            var original = $(e.target).hasClass('ui-draggable') ? $(e.target) : $(e.target).closest('.ui-draggable');
            return original.clone().css({
              width: original.width()
            });
          },
          start: function (e, ui) {
            $('.ipe-droppable').addClass('active');
            // Remove the droppable regions closest to this block.
            $(e.target).next('.ipe-droppable').removeClass('active');
            $(e.target).prev('.ipe-droppable').removeClass('active');
          },
          stop: function (e, ui) {
            $('.ipe-droppable').removeClass('active');
          },
          opacity: .5
        });
      }

      // Add a special class if we're currently syncing HTML from the server.
      if (this.model.get('syncing')) {
        this.$el.addClass('syncing');
      }

      return this;
    },

    /**
     * Overrides the default remove function to make a copy of our current HTML
     * into the Model for future rendering. This is required as modules like
     * Quickedit modify Block HTML without our knowledge.
     *
     * @return {Drupal.panels_ipe.BlockView}
     *   Return this, for chaining.
     */
    remove: function () {
      // Remove known augmentations to HTML so that they do not persist.
      this.$('.ipe-actions-block').remove();
      this.$el.removeClass('ipe-highlight active');

      // Update our Block model HTML based on our current visual state.
      this.model.set({html: this.$el.prop('outerHTML')});

      // Call the normal Backbow.view.remove() routines.
      this._removeElement();
      this.stopListening();
      return this;
    },

    /**
     * Reacts to our model being synced from the server.
     */
    finishedSync: function () {
      this.model.set('syncing', false);
    },

    styleBlock: function() {
      var info = {
        url: Drupal.panels_ipe.urlRoot(drupalSettings) + '/block_plugins/' + this.model.get('id') + '/block/' + this.model.get('uuid') + '/style_form',
        model: this.model
      };

      Drupal.panels_ipe.app_view.loadBlockForm(info);
    }

  });
}(jQuery, _, Backbone, Drupal));