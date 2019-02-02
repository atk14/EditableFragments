Editable Fragments
------------------

Installation
============

    cd path/to/your/project/
    git submodule add git@bitbucket.org:snapps/editable_fragments.git lib/editable_fragments

    ln -s ../../../lib/editable_fragments/app/controllers/admin/editable_fragments_controller.php app/controllers/admin/
    ln -s ../../../lib/editable_fragments/app/forms/admin/editable_fragments app/forms/admin/
    ln -s ../../../lib/editable_fragments/app/views/admin/editable_fragments app/views/admin/
    ln -s ../../lib/editable_fragments/app/helpers/block.editable.php app/helpers/
    ln -s ../../lib/editable_fragments/app/helpers/block.editable_markdown.php app/helpers/
    ln -s ../../lib/editable_fragments/app/helpers/block.editable_page_description.php app/helpers/
    ln -s ../../lib/editable_fragments/app/helpers/block.editable_page_title.php app/helpers/
    ln -s ../../lib/editable_fragments/app/helpers/function.editable_render.php app/helpers/
    ln -s ../../lib/editable_fragments/app/models/editable_fragment.php app/models/
    ln -s ../../lib/editable_fragments/app/models/editable_fragment_history.php app/models/
    ln -s ../../lib/editable_fragments/public/styles/editable_fragments.less public/styles/
    ln -s ../../lib/editable_fragments/test/tc_editable_fragment.php test/models/
    ln -s ../../lib/editable_fragments/test/tc_editable_fragment_history.php test/models/

Copy migration to a proper filename into your project:

    cp lib/editable_fragments/db/migrations/0144_editable_fragments.sql db/migrations/

