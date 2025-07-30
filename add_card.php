<?php require_once 'templates/header.php'; ?>
<section class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Καταχώριση νέας κάρτας</h5>
            </div>
            <div class="card-body">
                <form id="addForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Κωδικός κάρτας</label>
                            <input type="text" name="card_code" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ονοματεπώνυμο</label>
                            <input type="text" name="fullname" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Περιγραφή</label>
                            <input type="text" name="description" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ημ/νία αγοράς</label>
                            <input type="date" name="purchase_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Είδος</label>
                            <select name="type" class="form-select" required>
                                <option value="Συνεργάτης">Συνεργάτης</option>
                                <option value="Πελάτης">Πελάτης</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Τιμή (€)</label>
                            <input type="number" name="price" step="0.01" class="form-control" required>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-cherry">Αποθήκευση</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require_once 'templates/footer.php'; ?>