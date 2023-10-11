<?php

namespace App\Containers\ReleaseVueJS\UI\WEB\Controllers;

use App\Containers\ReleaseVueJS\Actions\CreateReleaseVueJSAction;
use App\Containers\ReleaseVueJS\Actions\DeleteBulkReleaseVueJSAction;
use App\Containers\ReleaseVueJS\Actions\DeleteReleaseVueJSAction;
use App\Containers\ReleaseVueJS\Actions\FindReleaseVueJSByIdAction;
use App\Containers\ReleaseVueJS\Actions\GetAllReleaseVueJsAction;

use App\Containers\ReleaseVueJS\Actions\UpdateReleaseVueJSAction;
use App\Containers\ReleaseVueJS\UI\WEB\Requests\CreateReleaseVueJSRequest;
use App\Containers\ReleaseVueJS\UI\WEB\Requests\DeleteBulkReleaseVueJSRequest;
use App\Containers\ReleaseVueJS\UI\WEB\Requests\DeleteReleaseVueJSRequest;
use App\Containers\ReleaseVueJS\UI\WEB\Requests\GetAllReleaseVueJsRequest;
use App\Containers\ReleaseVueJS\UI\WEB\Requests\FindReleaseVueJSByIdRequest;
use App\Containers\ReleaseVueJS\UI\WEB\Requests\UpdateReleaseVueJSRequest;
use App\Containers\ReleaseVueJS\UI\WEB\Requests\StoreReleaseVueJSRequest;
use App\Containers\ReleaseVueJS\UI\WEB\Requests\EditReleaseVueJSRequest;

use App\Ship\Parents\Controllers\WebController;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Http\File;
use Illuminate\Support\Facades\App;
use Intervention\Image\Facades\Image;
use Storage;
use Exception;

/**
 * Class Controller
 *
 * @package App\Containers\Release\UI\WEB\Controllers
 */
class Controller extends WebController
{
    /**
     * Show all entities
     *
     * @param GetAllReleaseVueJsRequest $request
     */
    public function getAllRelease(GetAllReleaseVueJsRequest $request)
    {
        $releases = App::make(GetAllReleaseVueJsAction::class)->run();

        if ($request->expectsJson()) {
            return response()->json(
                $releases,
            );
        }
        return view('releasevuejs::admin.admin-show-release-page', compact('releases'));
    }

    /**
     * Show one entity
     *
     * @param FindReleaseVueJSByIdRequest $request
     */
    public function showDetailRelease(FindReleaseVueJSByIdRequest $request)
    {
        $release = App::make(FindReleaseVueJSByIdAction::class)->run(new DataTransporter($request));

        return view('releasevuejs::admin.admin-show-detail-page', compact('release'));
    }

    /**
     * Create entity (show UI)
     *
     * @param CreateReleaseVueJSRequest $request
     */
    public function create(CreateReleaseVueJSRequest $request)
    {
        return view('releasevuejs::admin.admin-create-release-page');
    }

    /**
     * Add a new entity
     *
     * @param StoreReleaseVueJSRequest $request
     */
    public function store(StoreReleaseVueJSRequest $request)
    {
        $requestData = $request->only(['name', 'title_description', 'detail_description', 'is_publish', 'images']);

        if ($request->hasFile('images_from_quill')) {
            $detail_description = $requestData['detail_description'];
            $images_from_quill  = $request->images_from_quill;
            foreach ($images_from_quill as $key => $file) {
                $name = time() . rand(1, 100) . '.' . $file->getClientOriginalName();

                $this->resizeAndSaveImage($file, $name);

                $requestData['images'][$key] = '/storage/images-release/' . $name;

                $detail_description = str_replace('src="image_' . $key . '"', 'src="/storage/images-release/' . $name . '"', $detail_description);
            }
            $requestData['detail_description'] = $detail_description;

        } else
            $requestData['images'] = [];

        if ($request->images) {
            foreach ($request->images as $key => $file) {
                $name = time() . rand(1, 100) . '.' . $file->getClientOriginalName();

                $this->resizeAndSaveImage($file, $name);

                $requestData['images'][] = '/storage/images-release/' . $name;
            }
        }

        try {
            $release = App::make(CreateReleaseVueJSAction::class)->run(new DataTransporter($requestData));
        } catch (Exception $e) {
            \Log::error($e);
            return redirect()->route('web_releasevuejs_create')->with('error', '<p>Release <strong>' . $requestData['name'] . '</strong> Created Failed</p>');
        }

        return redirect()->route('web_releasevuejs_create')->with('success', '<p>Release <strong>' . $release->name . '</strong> Created Successfully</p>');
    }

    /**
     * Edit entity (show UI)
     *
     * @param EditReleaseVueJSRequest $request
     */
    public function edit(EditReleaseVueJSRequest $request)
    {
        $release = App::make(FindReleaseVueJSByIdAction::class)->run(new DataTransporter($request));
        if ($request->expectsJson()) {
            return $release;
        }
        return view('releasevuejs::admin.admin-create-release-page', compact('release'));
    }

    /**
     * Update a given entity
     *
     * @param UpdateReleaseVueJSRequest $request
     */
    public function update(UpdateReleaseVueJSRequest $request)
    {
        try {
            $result      = App::make(FindReleaseVueJSByIdAction::class)->run(new DataTransporter($request));
            $requestData = $request->only(['id', 'name', 'title_description', 'detail_description', 'is_publish']);

            if ($result->images) {
                if ($request->images_old) {
                    foreach ($result->images as $key => $value) {
                        if (!in_array($value, $request->images_old)) {
                            Storage::disk('public')->delete(substr($value, 8));
                        }
                    }
                    $requestData['images'] = $request->images_old;
                } else {
                    foreach ($result->images as $key => $value) {
                        Storage::disk('public')->delete(substr($value, 8));
                    }
                    $requestData['images'] = [];
                }
            } else {
                $requestData['images'] = [];
            }

            if ($request->hasFile('images_from_quill')) {
                $detail_description = $requestData['detail_description'];
                $images_from_quill  = $request->images_from_quill;
                foreach ($images_from_quill as $key => $file) {
                    $name = time() . rand(1, 100) . '.' . $file->getClientOriginalName();

                    $this->resizeAndSaveImage($file, $name);

                    $requestData['images'][] = '/storage/images-release/' . $name;
                    $detail_description      = str_replace('src="image_' . $key . '"', 'src="/storage/images-release/' . $name . '"', $detail_description);
                }
                $requestData['detail_description'] = $detail_description;
            }

            if ($request->images) {
                foreach ($request->images as $file) {
                    $name = time() . rand(1, 100) . '.' . $file->getClientOriginalName();

                    $this->resizeAndSaveImage($file, $name);

                    $requestData['images'][] = '/storage/images-release/' . $name;
                }
            }
            $release = App::make(UpdateReleaseVueJSAction::class)->run(new DataTransporter($requestData));

        } catch (Exception $e) {
            \Log::error($e);
            return redirect()->route('web_releasevuejs_edit', [$request->id])->with('error', '<p>Release <strong>' . $request->name . '</strong> Updated Failed</p>');
        }
        return redirect()->route('web_releasevuejs_edit', [$release->id])->with('success', '<p>Release <strong>' . $release->name . '</strong> Updated Successfully</p>');
    }

    /**
     * Delete a given entity
     *
     * @param DeleteReleaseVueJSRequest $request
     */
    public function delete(DeleteReleaseVueJSRequest $request)
    {
        try {
            $result = App::make(FindReleaseVueJSByIdAction::class)->run(new DataTransporter($request));

            App::make(DeleteReleaseVueJSAction::class)->run(new DataTransporter($request));
            if ($result->images != null) {
                foreach ($result->images as $value) {
                    Storage::disk('public')->delete(substr($value, 8));
                }
            }
        } catch (Exception $e) {
            \Log::error($e);
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => '',
                    'error'   => '<p style="color:red"> Release Not Found </p>',
                ]);
            }
            return redirect()->route('web_releasevuejs_get_all_release')->with('error', '<p style="color:red"> Release Not Found </p>');
        }
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Release deleted successfully',
                'success' => "<p style='color:blue'>Release <strong>" . $result->name . "</strong> Deleted Successfully</p>",
            ]);
        }

        return redirect()->route('web_releasevuejs_get_all_release')->with('success', '<p style="color:blue">Release <strong>' . $result->name . '</strong> Deleted Successfully</p>');
    }
    public function deleteBulk(DeleteBulkReleaseVueJSRequest $request)
    {
        try {
            $result = App::make(FindReleaseVueJSByIdAction::class)->run(new DataTransporter($request));

            $releaseName = '';
            foreach ($result as $value) {
                $releaseName .= $value->name . ', ';
            }
            $releaseName = substr($releaseName, 0, -2);

            App::make(DeleteBulkReleaseVueJSAction::class)->run(new DataTransporter($request));

            foreach ($result as $item) {
                if ($item->images != null) {
                    foreach ($item->images as $image) {
                        Storage::disk('public')->delete(substr($image, 8));
                    }
                }
            }

        } catch (Exception $e) {
            \Log::error($e);
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => '',
                    'error'   => '<p style="color:red"> Release(s) Not Found </p>',
                ]);
            }
            return redirect()->route('web_releasevuejs_get_all_release')->with('error', '<p style="color:red"> Release(s) Not Found </p>');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Release(s) deleted successfully',
                'success' => '<p style="color:blue"> Release <strong>' . $releaseName . '</strong> Deleted Successfully </p>',
            ]);
        }
        return redirect()->route('web_releasevuejs_get_all_release')->with('success', '<p style="color:blue"> Release <strong>' . $releaseName . '</strong> Deleted Successfully </p>');
    }

    function resizeAndSaveImage($file, $name)
    {
        $image_resize = Image::make($file->getRealPath());
        $image_resize->resize(400, 400);
        $image_resize->save(public_path('storage/images/' . $name));

        $saved_image_uri = $image_resize->dirname . '/' . $name;

        Storage::disk('public')->putFileAs('images-release', new File($saved_image_uri), $name, 'public');

        $image_resize->destroy();
        unlink($saved_image_uri);
    }
}