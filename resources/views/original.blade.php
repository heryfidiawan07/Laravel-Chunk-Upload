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
            <div class="card mx-auto w-75 p-3">
                <h2 class="text-center">Resumable Chunk File</h2>
                @csrf
                <div class="text-center">
                    <div id="resumable-error" style="display: none">
                        Resumable not supported
                    </div>
                    <div id="resumable-drop" style="display: none">
                        <p>
                            <button id="resumable-browse" data-url="{{ route('chunk.store') }}" >Upload</button>
                            or drop here
                        </p>
                        <p></p>
                    </div>
                    
                    <ul id="file-upload-list" class="list-unstyled"  style="display: none">
                    </ul>

                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">75%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.1.0/resumable.min.js"></script>

        <script>
        // use the global jQuery instance
        let $ = window.$ 

        let $fileUpload = $('#resumable-browse')
        let $fileUploadDrop = $('#resumable-drop')
        let $uploadList = $("#file-upload-list")

        if ($fileUpload.length > 0 && $fileUploadDrop.length > 0) {
            let resumable = new Resumable({
                // Use chunk size that is smaller than your maximum limit due a resumable issue
                // https://github.com/23/resumable.js/issues/51
                chunkSize: (1 * 1024 * 1024) * 5, // (1 MB) // * 5 = 5 MB
                simultaneousUploads: 3,
                testChunks: false,
                throttleProgressCallbacks: 1,
                // Get the url from data-url tag
                target: $fileUpload.data('url'),
                // Append token to the request - required for web routes
                query:{_token : $('input[name=_token]').val()}
            })

            // Resumable.js isn't supported, fall back on a different method
            if (!resumable.support) {
                $('#resumable-error').show()
            } else {
                // Show a place for dropping/selecting files
                $fileUploadDrop.show()
                resumable.assignDrop($fileUpload[0])
                resumable.assignBrowse($fileUploadDrop[0])

                // Handle file add event
                resumable.on('fileAdded', function (file) {
                    // Show progress pabr
                    $uploadList.show()
                    // Show pause, hide resume
                    $('.resumable-progress .progress-resume-link').hide()
                    $('.resumable-progress .progress-pause-link').show()
                    // Add the file to the list
                    $uploadList.append('<li class="resumable-file-' + file.uniqueIdentifier + '">Uploading <span class="resumable-file-name"></span> <span class="resumable-file-progress"></span>')
                    $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-name').html(file.fileName)
                    // Actually start the upload
                    resumable.upload()
                })
                resumable.on('fileSuccess', function (file, message) {
                    // Reflect that the file upload has completed
                    $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-progress').html('(completed)')
                })
                resumable.on('fileError', function (file, message) {
                    // Reflect that the file upload has resulted in error
                    $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-progress').html('(file could not be uploaded: ' + message + ')')
                })
                resumable.on('fileProgress', function (file) {
                    // Handle progress for both the file and the overall upload
                    $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-progress').html(Math.floor(file.progress() * 100) + '%')
                    $('.progress-bar').css({width: Math.floor(resumable.progress() * 100) + '%'})
                })
            }
        }
        </script>

    </body>
</html>