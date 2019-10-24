Editable Fragments
------------------

Usage
=====

In a template:

    {* file: app/views/main/index.tpl *}

    {editable key="about_us"}
    <p>We are who we are!</p>
    <p>We are here because we are here!</p>
    {/editable}

By default, editable content is being edited in the textarea (as the DEFAULT_EDITABLE_CONTENT_TYPE is set as "text").

Other ways of usage:

    {editable_string key="about_us/title"}About Us{/editable_string}

    {editable_markdown key="about_us"}
    * We are who we are!
    * We are here because we are here!
    {/editable_markdown}

    {editable_render partial="person_info" person=$person key="vip_person"}


    {editable_link_content key="about_us/title"}
      <a  href="{"about_us"|link_to_page}">
        About us
      </a>
    {/editable_link_content}

Prerequisites
=============

### User Authorization

### Iobjects

### Icons

When in the application is constant USING_FONTAWESOME defined to true, Fontawesome icons are used automatically. Otherwise, Ionicons icons are used.


Configuration
=============

    define("DEFAULT_EDITABLE_CONTENT_TYPE","text");
    define("DEFAULT_EDITABLE_CONTENT_SECTION","content");
    define("DEFAULT_EDITABLE_KEY","content");

Installation
============

    cd path/to/your/project/
    git submodule add git@bitbucket.org:snapps/editable_fragments.git lib/editable_fragments

    ln -s ../../../lib/editable_fragments/app/controllers/admin/editable_fragments_controller.php app/controllers/admin/
    ln -s ../../../lib/editable_fragments/app/forms/admin/editable_fragments app/forms/admin/
    ln -s ../../../lib/editable_fragments/app/views/admin/editable_fragments app/views/admin/
    ln -s ../../lib/editable_fragments/app/helpers/block.editable.php app/helpers/
    ln -s ../../lib/editable_fragments/app/helpers/block.editable_markdown.php app/helpers/
    ln -s ../../lib/editable_fragments/app/helpers/block.editable_link_content.php app/helpers/
    ln -s ../../lib/editable_fragments/app/helpers/block.editable_page_description.php app/helpers/
    ln -s ../../lib/editable_fragments/app/helpers/block.editable_page_title.php app/helpers/
    ln -s ../../lib/editable_fragments/app/helpers/function.editable_render.php app/helpers/
    ln -s ../../lib/editable_fragments/app/helpers/block.editable_string.php app/helpers/
    ln -s ../../lib/editable_fragments/app/models/editable_fragment.php app/models/
    ln -s ../../lib/editable_fragments/app/models/editable_fragment_history.php app/models/
    ln -s ../../lib/editable_fragments/test/models/tc_editable_fragment.php test/models/
    ln -s ../../lib/editable_fragments/test/models/tc_editable_fragment_history.php test/models/
    ln -s ../../lib/editable_fragments/test/helpers/tc_editable_markdown.php test/helpers/

Copy migration to a proper filename into your project:

    cp lib/editable_fragments/db/migrations/0144_editable_fragments.sql db/migrations/

Linking a proper style form either for Bootstrap 3 (less) or Bootstrap 4 (scss).

    ln -s ../../lib/editable_fragments/public/styles/editable_fragments.less public/styles/

    # or

    ln -s ../../lib/editable_fragments/public/styles/_editable_fragments.scss public/styles/

Now include the selected style to your application style.

[//]: # ( vim: set ts=2 et: )
