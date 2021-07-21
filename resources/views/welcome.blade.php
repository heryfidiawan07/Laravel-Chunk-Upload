<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <title>Hello, world!</title>
    </head>
    <body>
        
        <div class="container mt-5">
            <div class="card mx-auto w-50">
                <h2>Upload CSV</h2>
                <form action="{{route('upload.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group border-primary">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="file" name="file" required>
                            <label class="custom-file-label" for="file">Choose file</label>
                        </div>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary" type="button">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        {{-- <input type="file" id="f" /> --}}

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

 
<script type="text/javascript">
(function() {

var f = document.getElementById('f');

if (f.files.length)
  processFile();

f.addEventListener('change', processFile, false);


function processFile(e) {
  var file = f.files[0];
  var size = file.size;
  // console.log('files size', size)
  // return
  var sliceSize = 68616736;
  var start = 0;

  setTimeout(loop, 1);

  function loop() {
    var end = start + sliceSize;
    
    if (size - end < 0) {
      end = size;
    }
    
    var s = slice(file, start, end);

    send(s, start, end);

    if (end < size) {
      start += sliceSize;
      setTimeout(loop, 1);
    }
  }
}


function send(piece, start, end) {
  var formdata = new FormData();
  var xhr = new XMLHttpRequest();

  xhr.open('POST', 'http://localhost:8000/upload/store', true);

  formdata.append('start', start);
  formdata.append('end', end);
  formdata.append('file', piece);

  xhr.send(formdata);
}

/**
 * Formalize file.slice
 */

function slice(file, start, end) {
  var slice = file.mozSlice ? file.mozSlice :
              file.webkitSlice ? file.webkitSlice :
              file.slice ? file.slice : noop;
  
  return slice.bind(file)(start, end);
}

function noop() {
  
}

})();
</script>


    </body>
</html>