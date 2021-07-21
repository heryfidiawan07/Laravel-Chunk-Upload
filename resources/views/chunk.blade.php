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
            <div class="card mx-auto w-100 p-3">
                <h2 class="text-center">Resumable Chunk File</h2>
                <div class="text-center">
                    
                    <div id="resumable-error" style="display: none">
                        Browser not supported
                    </div>
                    <div id="resumable-drop" style="display: none">
                        @csrf
                        <button class="btn btn-light mb-2" id="resumable-browse" data-url="{{ route('chunk.store') }}">
                            Upload
                        </button>
                    </div>
                    <div id="progress-frame" style="display: none;"></div> 
                    
                </div>
            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.1.0/resumable.min.js"></script> --}}
        <script src="{{asset('resumable.js')}}"></script>

        <script>
        if ($('#resumable-browse').length > 0 && $('#resumable-drop').length > 0) {
            let resumable = new Resumable({
                chunkSize: 1 * 1024 * 1024, // (1 MB)
                simultaneousUploads: 3,
                testChunks: false,
                throttleProgressCallbacks: 1,
                target: $('#resumable-browse').data('url'),
                query:{_token : $('input[name=_token]').val()}
            })
            chunkUpload(resumable)
        }

        function chunkUpload(resumable)
        {
            if (!resumable.support) {
                $('#resumable-error').show()
            } else {
                $('#resumable-drop').show()
                resumable.assignDrop($('#resumable-browse')[0])
                resumable.assignBrowse($('#resumable-drop')[0])

                resumable.on('fileAdded', function (file) {
                    $("#progress-frame").show()
                    $("#progress-frame").append('<div class="progress-data-'+file.uniqueIdentifier+'"><h5 class="file-name text-center"></h5></div><div class="progress progress-value-'+file.uniqueIdentifier+'"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">0%</div></div>')

                    $('.progress-data-'+file.uniqueIdentifier+' .file-name').text(file.fileName+' (Uploading)')
                    
                    console.log('file',file.fileName)
                    resumable.upload()
                })
                resumable.on('fileSuccess', function (file, message) {
                    console.log('fileSuccess')
                    $('.progress-data-'+file.uniqueIdentifier+' .file-name').text(file.fileName+' (Completed)')
                })
                resumable.on('fileError', function (file, message) {
                    console.log('fileError')
                    $('.progress-data-'+file.uniqueIdentifier+' .file-name').text(file.fileName+' (Error !)')
                })
                resumable.on('fileProgress', function (file) {
                    console.log('fileProgress')

                    let percent = Math.floor(resumable.progress() * 100)
                    $('.progress-value-'+file.uniqueIdentifier+' .progress-bar').attr('aria-valuenow', percent)
                    $('.progress-value-'+file.uniqueIdentifier+' .progress-bar').css('width', `${percent}%`)
                    $('.progress-value-'+file.uniqueIdentifier+' .progress-bar').text(`${percent}%`)
                })
            }
        }
        </script>

    </body>
</html>