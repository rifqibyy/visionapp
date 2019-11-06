<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style>
        .file-name {
            width: 200px;
            border-radius: 0 !important;
        }

        .button {
            border-radius: 0 5px 5px 0 !important;
        }
    </style>
</head>

<body>
    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column">
                    <h4 class="title is-4">Select Your Picture</h4>
                    <form action="<?= base_url('vision/dosomemagic') ?>" method="post" enctype="multipart/form-data">
                        <div id="picture-file">
                            <div class="file is-small has-name">
                                <label class="file-label">
                                    <input class="file-input" id="file-input" type="file" name="img">
                                    <span class="file-cta">
                                        <span class="file-icon">
                                            <i class="fas fa-upload"></i>
                                        </span>
                                        <span class="file-label">
                                            Choose a file…
                                        </span>
                                    </span>
                                    <span class="file-name">

                                    </span>
                                </label>
                                <button type="submit" class="button is-primary is-small">Upload</button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <table class="table " width="100%">
                        <tr>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                        <?php foreach ($datas as $row) : ?>
                            <tr>
                                <td><img src="https://rifqiblob.blob.core.windows.net/img/<?= $row->img ?>" alt="" style="height: 50px;"></td>
                                <td><a href="<?= base_url('vision/show/' . $row->id) ?>">Show</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <div class="column">
                    <?php if (isset($data)) : ?>
                        <img id="sourceImage" src="https://rifqiblob.blob.core.windows.net/img/<?= $data->img ?>" alt="" style="max-height:500px">
                        <p id="desc">This is : </p>
                        <!-- <p>Detail : </p>
                        <textarea name="responseTextArea" id="responseTextArea" class="textarea" rows="10"></textarea> -->
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <script>
        $("#file-input").change(function() {
            var filename = this.files[0].name
            $("#picture-file .file-name").text(filename)
        })
    </script>

    <?php if (isset($data)) : ?>
        <script type="text/javascript">
            $(document).ready(function() {
                // **********************************************
                // *** Update or verify the following values. ***
                // **********************************************

                // Replace <Subscription Key> with your valid subscription key.
                var subscriptionKey = "978648c5a78c46819ff492c3b0dec573";

                // You must use the same Azure region in your REST API method as you used to
                // get your subscription keys. For example, if you got your subscription keys
                // from the West US region, replace "westcentralus" in the URL
                // below with "westus".
                //
                // Free trial subscription keys are generated in the "westus" region.
                // If you use a free trial subscription key, you shouldn't need to change
                // this region.
                var uriBase =
                    "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";

                // Request parameters.
                var params = {
                    "visualFeatures": "Categories,Description,Color",
                    "details": "",
                    "language": "en",
                };

                // Display the image.
                var sourceImageUrl = "https://rifqiblob.blob.core.windows.net/img/<?= $data->img ?>"

                // Make the REST API call.
                $.ajax({
                        url: uriBase + "?" + $.param(params),

                        // Request headers.
                        beforeSend: function(xhrObj) {
                            xhrObj.setRequestHeader("Content-Type", "application/json");
                            xhrObj.setRequestHeader(
                                "Ocp-Apim-Subscription-Key", subscriptionKey);
                        },

                        type: "POST",

                        // Request body.
                        data: '{"url": ' + '"' + sourceImageUrl + '"}',
                    })

                    .done(function(data) {
                        // Show formatted JSON on webpage.
                        // $("#responseTextArea").val(JSON.stringify(data, null, 2));
                        $("#desc").append(data.description.captions[0].text)
                        console.log(data.description.captions[0].text)
                    })

                    .fail(function(jqXHR, textStatus, errorThrown) {
                        // Display error message.
                        var errorString = (errorThrown === "") ? "Error. " :
                            errorThrown + " (" + jqXHR.status + "): ";
                        errorString += (jqXHR.responseText === "") ? "" :
                            jQuery.parseJSON(jqXHR.responseText).message;
                        alert(errorString);
                    });
            })
        </script>
    <?php endif; ?>

</body>

</html>