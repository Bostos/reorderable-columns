import Sortable from 'sortablejs';

function initSortable(element, tableId) {
    new Sortable(element, {
        animation: 150,
        ghostClass: 'reorderable-ghost',
        filter: '.fi-ta-selection-cell, .fi-ta-actions-header-cell',

            onEnd: function (evt) {
                const newOrder = Array.from(this.el.children)
                    .map(th => {
                        for (const className of th.classList) {
                            const match = className.match(/^fi-table-header-cell-(.*)$/);
                            if (match && match[1]) {
                                return match[1].replace(/-/g, '_');
                            }
                        }
                        return null;
                    })
                    .filter(Boolean);

                const item = evt.item;
                const parent = evt.from;

                parent.removeChild(item);

                if (evt.oldDraggableIndex < parent.children.length) {
                    parent.insertBefore(item, parent.children[evt.oldDraggableIndex]);
                } else {
                    parent.appendChild(item);
                }

                const livewireComponent = window.Livewire.find(element.closest('[wire\\:id]').getAttribute('wire:id'));

                if (livewireComponent && newOrder.length > 0) {
                        livewireComponent.call('reorderTableColumns', tableId, newOrder)
                            .then(() => {
                                livewireComponent.call('$refresh');
                            });
                    } else {
                        console.error('Reorderable columns: Could not find Livewire component or failed to parse new order.');
                    }
            }
    });
}

function findAndInitTables() {
    const wrappers = document.querySelectorAll('[data-reorderable-columns]');
    wrappers.forEach(wrapper => {
        if (wrapper.dataset.reorderableInitialized) return;

        const table = wrapper.querySelector('table');
        if (!table) return;

        const tableId = wrapper.dataset.reorderableColumns;
        if (!tableId) return;

        wrapper.dataset.reorderableInitialized = 'true';

        const observer = new MutationObserver(() => {
            const thead = table.querySelector('thead > tr');
            if (thead) {
                initSortable(thead, tableId);
                observer.disconnect();
            }
        });

        observer.observe(table, { childList: true, subtree: true });

        const initialThead = table.querySelector('thead > tr');
        if (initialThead) {
            initSortable(initialThead, tableId);
            observer.disconnect();
        }
    });
}

document.addEventListener('livewire:navigated', findAndInitTables);
document.addEventListener('DOMContentLoaded', findAndInitTables);

findAndInitTables();
