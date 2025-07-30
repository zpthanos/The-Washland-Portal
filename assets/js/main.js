(() => {
  'use strict';

  
  const ENDPOINTS = {
    stats:  'api/stats.php',
    read:   'api/card_read.php',
    create: 'api/card_create.php',
    update: 'api/card_update.php',
    delete: 'api/card_delete.php'
  };

  const SELECTORS = {
    table:     '#cardsTable',
    addForm:   '#addForm',
    editForm:  '#editForm',
    editModal: '#editModal',
    saveEdit:  '#saveEdit',
    toast:     '#liveToast',
    total: {
      cards:   '#totalCards',
      sales:   '#totalSales',
      partner: '#partnerCards',
      client:  '#clientCards'
    }
  };

  
  const renderType  = t =>
    t === 'Συνεργάτης'
      ? '<span class="badge badge-partner">Συνεργάτης</span>'
      : '<span class="badge badge-client">Πελάτης</span>';

  const renderPrice = p => {
    const n = Number(p);
    return Number.isFinite(n) ? n.toFixed(2) : '–';
  };

  const renderDate = d => {
    if (!d) return '';
    const [y, m, day] = d.split('-');
    return (y && m && day) ? `${day}-${m}-${y}` : d;
  };

  function showToast(message, delay = 3000) {
    const toastEl = document.querySelector(SELECTORS.toast);
    if (!toastEl) return;
    toastEl.querySelector('.toast-body').textContent = message;
    new bootstrap.Toast(toastEl, { delay }).show();
  }

 
  async function fetchWithTimeout(resource, options = {}, timeout = 8000) {
    const controller = new AbortController();
    const id = setTimeout(() => controller.abort(), timeout);
    try {
      const res = await fetch(resource, { ...options, signal: controller.signal, cache: 'no-store' });
      if (!res.ok) {
        throw new Error(`Network error: ${res.status} ${res.statusText}`);
      }
      const contentType = res.headers.get('content-type') || '';
      if (!contentType.includes('application/json')) {
        throw new Error('Invalid JSON response');
      }
      return await res.json();
    } finally {
      clearTimeout(id);
    }
  }

 
  class CardManager {
    constructor(endpoints, selectors) {
      this.urls = endpoints;
      this.sel  = selectors;
      this.$table = document.querySelector(this.sel.table);
      this.$addForm = document.querySelector(this.sel.addForm);
      this.$editForm = document.querySelector(this.sel.editForm);
      this.$saveEdit = document.querySelector(this.sel.saveEdit);
    }

    init() {
      if (this.$table)       this.initDataTable();
      if (this.$addForm)     this.initAddForm();
      this.loadStats().catch(e => console.error(e));
    }

   
    async loadStats() {
      try {
        const data = await fetchWithTimeout(this.urls.stats);
        document.querySelector(this.sel.total.cards).textContent   = data.total_cards;
        document.querySelector(this.sel.total.sales).textContent   = Number(data.total_sales).toFixed(2);
        document.querySelector(this.sel.total.partner).textContent = data.partner_cards;
        document.querySelector(this.sel.total.client).textContent  = data.client_cards;
      } catch (err) {
        console.error('Stats load failed:', err);
        showToast('Σφάλμα φόρτωσης στατιστικών');
      }
    }

    
    initDataTable() {
      this.dt = $(this.sel.table).DataTable({
        ajax:        { url: this.urls.read, dataSrc: '' },
        deferRender: true,
        responsive:  true,
        autoWidth:   false,
        columns: [
          {
            data: 'id',
            render: (data, type, row, meta) =>
              type === 'display' ? meta.row + 1 : data,
            type: 'num'
          },
          { data: 'card_code' },
          { data: 'fullname' },
          { data: 'description' },
          { data: 'purchase_date', render: renderDate },
          { data: 'type', render: renderType },
          { data: 'price', render: renderPrice },
          {
            data: null,
            orderable: false,
            defaultContent: `
              <button class="btn btn-sm btn-outline-primary edit-btn">
                <i class="bi bi-pencil-square"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger delete-btn">
                <i class="bi bi-trash"></i>
              </button>`
          }
        ],
        order: [[0, 'asc']],
        dom:   'Bfrtip',
        buttons: [
          { extend: 'excel', text: 'Excel' },
          { extend: 'pdf',   text: 'PDF' },
          { extend: 'print', text: 'Εκτύπωση' }
        ],
        language: { url: 'assets/js/datatables-greek.json' }
      });

     
      $(this.sel.table).on('click', '.edit-btn', e => this.openEditModal(e));
      $(this.sel.table).on('click', '.delete-btn', e => this.deleteRow(e));

    
      this.$saveEdit.addEventListener('click', () => this.saveEdit());
    }


    openEditModal(evt) {
      const rowData = this.dt.row($(evt.currentTarget).closest('tr')).data();
      if (!rowData) return;
      const mapping = {
        id:            'editId',
        card_code:     'editCode',
        fullname:      'editName',
        description:   'editDesc',
        purchase_date: 'editDate',
        type:          'editType',
        price:         'editPrice'
      };
      Object.entries(mapping).forEach(([key, fieldId]) => {
        this.$editForm.querySelector(`#${fieldId}`).value = rowData[key] ?? '';
      });
      new bootstrap.Modal(document.querySelector(this.sel.editModal)).show();
    }

 
    async saveEdit() {
      const btn = this.$saveEdit;
      btn.disabled = true;
      const originalText = btn.textContent;
      btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Φόρτωση...`;

      try {
        const formData = new URLSearchParams(new FormData(this.$editForm));
        const json = await fetchWithTimeout(this.urls.update, {
          method: 'POST',
          body: formData
        });
        if (!json.success) throw new Error(json.msg || 'Unknown error');

        bootstrap.Modal.getInstance(document.querySelector(this.sel.editModal)).hide();
        this.dt.ajax.reload(null, false);
        this.dt.columns.adjust().responsive.recalc();
        await this.loadStats();
        showToast('Η κάρτα ενημερώθηκε');
      } catch (err) {
        console.error('Update failed:', err);
        showToast(err.message || 'Σφάλμα ενημέρωσης — δοκιμάστε ξανά');
      } finally {
        btn.disabled = false;
        btn.textContent = originalText;
      }
    }

   
    async deleteRow(evt) {
      const rowData = this.dt.row($(evt.currentTarget).closest('tr')).data();
      if (!rowData || !confirm('Να διαγραφεί η κάρτα;')) return;

      try {
        const json = await fetchWithTimeout(this.urls.delete, {
          method: 'POST',
          body: new URLSearchParams({ id: rowData.id })
        });
        if (!json.success) throw new Error(json.msg || 'Unknown error');

        this.dt.ajax.reload(null, false);
        this.dt.columns.adjust().responsive.recalc();
        await this.loadStats();
        showToast('Η κάρτα διαγράφηκε');
      } catch (err) {
        console.error('Delete failed:', err);
        showToast(err.message || 'Σφάλμα διαγραφής — δοκιμάστε ξανά');
      }
    }

 
    initAddForm() {
      this.$addForm.addEventListener('submit', async e => {
        e.preventDefault();
        
        const submitBtn = this.$addForm.querySelector('button[type="submit"]');
        submitBtn.disabled = true;

        try {
          const formData = new URLSearchParams(new FormData(this.$addForm));
          const json = await fetchWithTimeout(this.urls.create, {
            method: 'POST',
            body: formData
          });
          if (!json.success) throw new Error(json.msg || 'Unknown error');
          showToast('Η κάρτα προστέθηκε');
          window.location.href = 'index.php';
        } catch (err) {
          console.error('Create failed:', err);
          showToast(err.message || 'Σφάλμα δημιουργίας — δοκιμάστε ξανά');
        } finally {
          submitBtn.disabled = false;
        }
      });
    }
  }

 
  document.addEventListener('DOMContentLoaded', () => {
    new CardManager(ENDPOINTS, SELECTORS).init();
  });

})();
