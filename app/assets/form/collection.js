import $ from 'jquery';

jQuery(document).ready(function () {
    // Get the ul that holds the collection
    const $collectionHolder = $('ul.form-collection-type');
    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('input').length);

    $('body').on('click', '.add_item_link', function (e) {
        const $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');
        // add a new element form (see next code block)
        addFormToCollection($collectionHolderClass);
    })
});

function addFormToCollection($collectionHolderClass)
{
    // Get the ul that holds the collection of element
    const $collectionHolder = $('.' + $collectionHolderClass);

    // Get the data-prototype explained earlier
    const prototype = $collectionHolder.data('prototype');

    // get the new index
    const index = $collectionHolder.data('index');

    let newForm = prototype;

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    const $newFormLi = $('<li class="list-group-item"></li>').append(newForm);
    // Add the new form at the end of the list
    $collectionHolder.append($newFormLi);

    // add a delete link to the new form
    addTagFormDeleteLink($newFormLi);
}

jQuery(document).ready(function () {
    // Get the ul that holds the collection of tags
    let $collectionHolder = $('ul.form-collection-type');
    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('li').each(function () {
        addTagFormDeleteLink($(this));
    });

    // ... the rest of the block from above
});

function addTagFormDeleteLink($tagFormLi)
{
    const $removeFormButton = $('<button class="mt-2 btn btn-danger" type="button">&cross;</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}