<?php require_once 'templates/header.php'; ?>
<section class="mb-4">
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card stat-card text-center border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Σύνολο Καρτών</h5>
                    <p class="display-6 text-white" id="totalCards">0</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card text-center border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Συνολικές Πωλήσεις (€)</h5>
                    <p class="display-6 text-white" id="totalSales">0</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card text-center border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Κάρτες Συνεργάτη / Πελάτη</h5>
                    <p class="display-6 text-white"><span id="partnerCards">0</span> / <span id="clientCards">0</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <table id="cardsTable" class="table table-striped w-100">
        <thead>
            <tr>
                <th>Αρ. Κάρτας</th>
                <th>Κωδικός Κάρτας</th>
                <th>Ονοματεπώνυμο</th>
                <th>Περιγραφή</th>
                <th>Ημ/νία Αγοράς</th>
                <th>Είδος</th>
                <th>Τιμή (€)</th>
                <th>Ενέργειες</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</section>
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Επεξεργασία κάρτας</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Κλείσιμο"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" name="id" id="editId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Κωδικός κάρτας</label>
                            <input type="text" name="card_code" id="editCode" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ονοματεπώνυμο</label>
                            <input type="text" name="fullname" id="editName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Περιγραφή</label>
                            <input type="text" name="description" id="editDesc" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ημ/νία αγοράς</label>
                            <input type="date" name="purchase_date" id="editDate" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Είδος</label>
                            <select name="type" id="editType" class="form-select" required>
                                <option value="Συνεργάτης">Συνεργάτης</option>
                                <option value="Πελάτης">Πελάτης</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Τιμή (€)</label>
                            <input type="number" step="0.01" name="price" id="editPrice" class="form-control" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Άκυρο</button>
                <button type="button" class="btn btn-cherry" id="saveEdit">Αποθήκευση</button>
            </div>
        </div>
    </div>
</div>
<?php require_once 'templates/footer.php'; ?>