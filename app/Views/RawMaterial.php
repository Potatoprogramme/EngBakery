<body class="bg-gray-50">
    <!-- Main Content -->
    <div class="p-4 sm:ml-60">
        <div class="p-4 mt-14 bg-white rounded-lg shadow-md">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Raw Materials</h2>
                <div class="flex flex-wrap gap-2">
                    <button type="button" class="inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary/40">
                        Add Material
                    </button>
                    <button type="button" class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Export
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="selection-table" class="min-w-full text-sm text-left text-gray-500">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            <span class="flex items-center">
                                Name
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                </svg>
                            </span>
                        </th>
                        <th scope="col" class="px-6 py-3" data-type="date" data-format="YYYY/DD/MM">
                            <span class="flex items-center">
                                Release Date
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                </svg>
                            </span>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <span class="flex items-center">
                                NPM Downloads
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                </svg>
                            </span>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <span class="flex items-center">
                                Growth
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                </svg>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Flowbite</td>
                        <td class="px-6 py-4">2021/25/09</td>
                        <td class="px-6 py-4">269000</td>
                        <td class="px-6 py-4">49%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">React</td>
                        <td class="px-6 py-4">2013/24/05</td>
                        <td class="px-6 py-4">4500000</td>
                        <td class="px-6 py-4">24%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Angular</td>
                        <td class="px-6 py-4">2010/20/09</td>
                        <td class="px-6 py-4">2800000</td>
                        <td class="px-6 py-4">17%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Vue</td>
                        <td class="px-6 py-4">2014/12/02</td>
                        <td class="px-6 py-4">3600000</td>
                        <td class="px-6 py-4">30%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Svelte</td>
                        <td class="px-6 py-4">2016/26/11</td>
                        <td class="px-6 py-4">1200000</td>
                        <td class="px-6 py-4">57%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Ember</td>
                        <td class="px-6 py-4">2011/08/12</td>
                        <td class="px-6 py-4">500000</td>
                        <td class="px-6 py-4">44%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Backbone</td>
                        <td class="px-6 py-4">2010/13/10</td>
                        <td class="px-6 py-4">300000</td>
                        <td class="px-6 py-4">9%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">jQuery</td>
                        <td class="px-6 py-4">2006/28/01</td>
                        <td class="px-6 py-4">6000000</td>
                        <td class="px-6 py-4">5%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Bootstrap</td>
                        <td class="px-6 py-4">2011/19/08</td>
                        <td class="px-6 py-4">1800000</td>
                        <td class="px-6 py-4">12%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Foundation</td>
                        <td class="px-6 py-4">2011/23/09</td>
                        <td class="px-6 py-4">700000</td>
                        <td class="px-6 py-4">8%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Bulma</td>
                        <td class="px-6 py-4">2016/24/10</td>
                        <td class="px-6 py-4">500000</td>
                        <td class="px-6 py-4">7%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Next.js</td>
                        <td class="px-6 py-4">2016/25/10</td>
                        <td class="px-6 py-4">2300000</td>
                        <td class="px-6 py-4">45%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Nuxt.js</td>
                        <td class="px-6 py-4">2016/16/10</td>
                        <td class="px-6 py-4">900000</td>
                        <td class="px-6 py-4">50%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Meteor</td>
                        <td class="px-6 py-4">2012/17/01</td>
                        <td class="px-6 py-4">1000000</td>
                        <td class="px-6 py-4">10%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Aurelia</td>
                        <td class="px-6 py-4">2015/08/07</td>
                        <td class="px-6 py-4">200000</td>
                        <td class="px-6 py-4">20%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Inferno</td>
                        <td class="px-6 py-4">2016/27/09</td>
                        <td class="px-6 py-4">100000</td>
                        <td class="px-6 py-4">35%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Preact</td>
                        <td class="px-6 py-4">2015/16/08</td>
                        <td class="px-6 py-4">600000</td>
                        <td class="px-6 py-4">28%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Lit</td>
                        <td class="px-6 py-4">2018/28/05</td>
                        <td class="px-6 py-4">400000</td>
                        <td class="px-6 py-4">60%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Alpine.js</td>
                        <td class="px-6 py-4">2019/02/11</td>
                        <td class="px-6 py-4">300000</td>
                        <td class="px-6 py-4">70%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Stimulus</td>
                        <td class="px-6 py-4">2018/06/03</td>
                        <td class="px-6 py-4">150000</td>
                        <td class="px-6 py-4">25%</td>
                    </tr>
                    <tr class="hover:bg-neutral-secondary-soft cursor-pointer">
                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">Solid</td>
                        <td class="px-6 py-4">2021/05/07</td>
                        <td class="px-6 py-4">250000</td>
                        <td class="px-6 py-4">80%</td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tableElement = document.getElementById('selection-table');
            if (!tableElement || typeof simpleDatatables === 'undefined') {
                return;
            }

            let multiSelect = true;
            let rowNavigation = false;
            let table = null;

            const resetTable = () => {
                if (table) {
                    table.destroy();
                }

                const options = {
                    rowRender: (row, tr) => {
                        if (!tr.attributes) {
                            tr.attributes = {};
                        }
                        if (!tr.attributes.class) {
                            tr.attributes.class = '';
                        }
                        if (row.selected) {
                            tr.attributes.class += ' selected';
                        } else {
                            tr.attributes.class = tr.attributes.class.replace(' selected', '');
                        }
                        return tr;
                    }
                };

                if (rowNavigation) {
                    options.rowNavigation = true;
                    options.tabIndex = 1;
                }

                table = new simpleDatatables.DataTable('#selection-table', options);

                table.data.data.forEach(data => {
                    data.selected = false;
                });

                table.on('datatable.selectrow', (rowIndex, event) => {
                    event.preventDefault();
                    const row = table.data.data[rowIndex];
                    if (row.selected) {
                        row.selected = false;
                    } else {
                        if (!multiSelect) {
                            table.data.data.forEach(data => {
                                data.selected = false;
                            });
                        }
                        row.selected = true;
                    }
                    table.update();
                });
            };

            const isMobile = window.matchMedia('(any-pointer:coarse)').matches;
            if (isMobile) {
                rowNavigation = false;
            }

            resetTable();
        });
    </script>