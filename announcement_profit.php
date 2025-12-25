
 <h2>Announcement Profit</h2>
 <form method="post" action="">
         <div class="row mb-3">
             <div class="col-4">
                <label for="id" class="form-label">ID</label>
                <input type="int" name="id" class="form-control" required>
            </div>
            <div class="col-4">
                <label for="category" class="form-label">Category</label>
                <select class="form-control" name="category" id="category" required>
                    <option value="">-- SELECT --</option>
                   <option value="">-- Savings --</option>
                   <option value="">-- Share --</option>
           
                    </select>
                    
                    
            </div>

            <div class="col-4">
                <label for="taka" class="form-label">Total Amount</label>
                <input type="number" name="taka" id="taka" class="form-control"  required>
            </div>
            
            <div class="col-4">
                <label for="taka" class="form-label">Total Profit</label>
                <input type="number" name="taka" id="taka" class="form-control"  required>
            </div>
            
            
            <div class="col-4">
                <label for="taka" class="form-label">seasion</label>
                <input type="number" name="taka" id="taka" class="form-control"  required>
            </div>
            
             <div class="col-4">
                <label for="details" class="form-label">Comment</label>
                <input type="text" name="comment" id="comment" class="form-control">
            </div>
            
      </div>

       

        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
</form>