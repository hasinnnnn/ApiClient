<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Data Post</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    </head>

    <body class="bg-light">
        <main class="container">
            <!-- START FORM -->
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action='' method='post'>
                    @csrf
                    @if (Route::current()->uri == 'posts/{id}')
                        @method('put')
                    @endif
                    <div class="mb-3 row">
                        <label for="title" class="col-sm-2 col-form-label">Judul</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='title' id="title"
                                value="{{ isset($data['title']) ? $data['title'] : old('title') }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="author" class="col-sm-2 col-form-label">Author</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='author' id="author"
                                value="{{ isset($data['author']) ? $data['author'] : old('author') }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="slug" class="col-sm-2 col-form-label">Slug</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='slug' id="slug"
                                value="{{ isset($data['slug']) ? $data['slug'] : old('slug') }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="body" class="col-sm-2 col-form-label">Body</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name='body' id="body"
                                value="{{ isset($data['body']) ? $data['body'] : old('body') }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10"><button type="submit" class="btn btn-primary"
                                name="submit">SIMPAN</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- AKHIR FORM -->
            @if (Route::current()->uri == 'posts')
                <!-- START DATA -->
                <div class="my-3 p-3 bg-body rounded shadow-sm">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="col-md-1">No</th>
                                <th class="col-md-2">Title</th>
                                <th class="col-md-2">Author</th>
                                <th class="col-md-1">Slug</th>
                                <th class="col-md-4">Body</th>
                                <th class="col-md-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach ($data['data'] as $post)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $post['title'] }}</td>
                                    <td>{{ $post['author'] }}</td>
                                    <td>{{ $post['slug'] }}</td>
                                    <td>{{ $post['body'] }}</td>
                                    <td>
                                        <a href="{{ url('posts/' . $post['id']) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ url('posts/' . $post['id']) }}" method="post"
                                            onsubmit="return confim('yakin hapus data?'" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" name="submit"
                                                class="btn btn-danger btn-sm">Del</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($data['links'])
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                @foreach ($data['links'] as $item)
                                    <li class="page-item {{ $item['active'] ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $item['url2'] }}">{!! $item['label'] !!}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    @endif

                </div>
                <!-- AKHIR DATA -->
            @endif
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
        </script>

    </body>

</html>
