import Sortable from 'stimulus-sortable'

export default class extends Sortable {
    static values = {
        url: String,
    };

    connect() {
        super.connect()
        console.log('Do what you want here.')

        // The sortable.js instance.
        this.sortable

        // Your options
        this.options

        // Your default options
        this.defaultOptions
    }

    // You can override the `onUpdate` method here.
    async onUpdate({item: t, newIndex: a}) {
        await fetch(`${this.urlValue + t.dataset.swipeId.toString()}_${(a+1).toString()}`, {
            method: 'PATCH',
        });
    }

    // You can set default options in this getter for all sortable elements.
    get defaultOptions() {
        return {
            animation: 500,
        }
    }
}