<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use App\Components\Recusive;
use App\Http\Requests\TourAddRequest;
use App\Models\Category;
use App\Models\TourImage;
use App\Trait\DeleteModalTrait;
use App\Trait\StorageImageTrait;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class TourController extends Controller
{
    use StorageImageTrait;
    use DeleteModalTrait;
    private $tour;
    private $category;
    private $tourImage;
    public function __construct(Tour $tour, Category $category, TourImage $tourImage)
    {
        $this->tour = $tour;
        $this->category = $category;
        $this->tourImage = $tourImage;
    }

    public function index()
    {
        $tours = $this->tour->latest()->paginate(5);
        $htmlOption = $this->getCategory($parentId = '');
        $key = request()->key;
        if($key){
            $tours = $this->tour->where('name','like',"%{$key}%")
                                        ->orderBy('id','desc')
                                        ->paginate(5);
        }
        return view('admin.tour.index', compact('tours', 'htmlOption'));
    }

    public function getCategory($parentId)
    {
        $categoriesRecusive = new Recusive($this->category->all());
        $htmlOption = $categoriesRecusive->getCategoryRecusive($parentId);
        return $htmlOption;
    }

    public function store(TourAddRequest $request)
    {
        try {
            DB::beginTransaction();
            $dataTourCreate = [
                'name' => $request->name,
                'price' => $request->price,
                'departure_day' => $request->departure_day,
                'return_day' => $request->return_day,
                'type_vehical' => $request->type_vehical,
                'description' => strip_tags($request->description),
                'category_id' => $request->category_id
            ];
            $dataUploadFeatureImage = $this->storgeImageUpload($request, 'feature_image_path', 'tour');

            if (!empty($dataUploadFeatureImage)) {
                $dataTourCreate['feature_image_name'] = $dataUploadFeatureImage['file_name'];
                $dataTourCreate['feature_image_path'] = $dataUploadFeatureImage['file_path'];
            }

            $tours = $this->tour->create($dataTourCreate);
            // Ảnh chi tiết

            foreach ($request->image_path as $fileItems) {
                $imageDetail = $this->storgeMultiImageUpload($fileItems, 'tour');
                $tours->images()->create([
                    'image_path' => $imageDetail['file_path'],
                    'image_name' => $imageDetail['file_name']
                ]);
            }

            DB::commit();
            return redirect()->route('tour.index')->with('ok', 'Thêm tour thành công!');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: ' . $exception->getMessage() . 'Line : ' . $exception->getLine());
        }
    }

    public function edit($id)
    {
        $tour = $this->tour->find($id);
        $tourImageDetail = $this->tourImage->where('tour_id', $id)->get();
        // dd($destinationImageDetail);
        return response()->json([
            'code' => 200,
            'tour' => $tour,
            'tourImageDetail' => $tourImageDetail,
        ], 200);
    }
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $dataTourUpdate = [
                'name' => $request->name,
                'price' => $request->price,
                'description' => strip_tags($request->description),
                'departure_day' => $request->departure_day,
                'return_day' => $request->return_day,
                'type_vehical' => $request->type_vehical,
                'category_id' => $request->category_id
            ];

            $dataUploadFeatureImage = $this->storgeImageUpload($request, 'feature_image_path', 'tour');
            // dd($dataUploadFeatureImage);
            if (!empty($dataUploadFeatureImage)) {
                $dataTourUpdate['feature_image_name'] = $dataUploadFeatureImage['file_name'];
                $dataTourUpdate['feature_image_path'] = $dataUploadFeatureImage['file_path'];
            }

            $tourId = $request->input('tourId');
            $this->tour->find($tourId)->update($dataTourUpdate);
            $tours = $this->tour->find($tourId);

            // Ảnh chi tiết
            if ($request->hasFile('image_path')) {
                $this->tourImage->where('tour_id', $tourId)->delete();
                foreach ($request->image_path as $fileItems) {
                    $imageDetail = $this->storgeMultiImageUpload($fileItems, 'tour');
                    $tours->images()->create([
                        'image_path' => $imageDetail['file_path'],
                        'image_name' => $imageDetail['file_name']
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('tour.index')->with('ok', 'Sửa tour thành công!');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: ' . $exception->getMessage() . 'Line : ' . $exception->getLine());
        }
    }
    public function destroy($id)
    {
        return $this->deleteModalTrait($id, $this->tour);
    }
}
