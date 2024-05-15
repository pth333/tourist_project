<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use App\Components\Recusive;
use App\Http\Requests\DestinationAddRequest;
use App\Models\Category;
use App\Models\DestinationImage;
use App\Trait\DeleteModalTrait;
use App\Trait\StorageImageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DestinationController extends Controller
{
    use StorageImageTrait;
    use DeleteModalTrait;
    private $destination;
    private $category;
    private $destinationImage;
    public function __construct(Destination $destination, Category $category, DestinationImage $destinationImage)
    {
        $this->destination = $destination;
        $this->category = $category;
        $this->destinationImage = $destinationImage;
    }
    public function index()
    {
        $destinations = $this->destination->latest()->paginate(5);
        $htmlOption = $this->getCategory($parentId = '');
        $key = request()->key;
        if($key){
            $destinations = $this->destination->where('name','like',"%{$key}%")
                                        ->orderBy('id','desc')
                                        ->paginate(5);
        }
        return view('admin.destination.index', compact('destinations', 'htmlOption'));
    }

    public function getCategory($parentId)
    {
        $categoriesRecusive = new Recusive($this->category->all());
        $htmlOption = $categoriesRecusive->getCategoryRecusive($parentId);
        return $htmlOption;
    }

    public function store(DestinationAddRequest $request)
    {
        try {
            DB::beginTransaction();
            $dataDestinationCreate = [
                'name' => $request->name,
                'address' => $request->address,
                'description' => strip_tags($request->description),
                'opening_time' => $request->opening_time,
                'closing_time' => $request->closing_time,
                'user_id' => auth()->id(),
                'category_id' => $request->category_id
            ];
            // dd(strip_tags($request->description));
            // dd($dataDestinationCreate);
            $dataUploadFeatureImage = $this->storgeImageUpload($request, 'feature_image_path', 'destination');
            // dd($dataUploadFeatureImage);
            if (!empty($dataUploadFeatureImage)) {
                $dataDestinationCreate['feature_image_name'] = $dataUploadFeatureImage['file_name'];
                $dataDestinationCreate['feature_image_path'] = $dataUploadFeatureImage['file_path'];
            }

            $destinations = $this->destination->create($dataDestinationCreate);
            // dd($request->image_path);
            // Ảnh chi tiết
            foreach ($request->image_path as $fileItems) {
                $imageDetail = $this->storgeMultiImageUpload($fileItems, 'destination');
                $destinations->images()->create([
                    'image_path' => $imageDetail['file_path'],
                    'image_name' => $imageDetail['file_name']
                ]);
            }
            DB::commit();
            return redirect()->route('destination.index')->with('ok', 'Thêm địa điểm thành công!');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: ' . $exception->getMessage() . 'Line : ' . $exception->getLine());
        }
    }
    public function edit($id)
    {
        $destination = $this->destination->find($id);
        $destinationImageDetail = $this->destinationImage->where('destination_id', $id)->get();
        // dd($destinationImageDetail);
        return response()->json([
            'code' => 200,
            'destination' => $destination,
            'destinationImage' => $destinationImageDetail,
        ], 200);
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $dataDestinationUpdate = [
                'name' => $request->name,
                'address' => $request->address,
                'description' => strip_tags($request->description),
                'opening_time' => $request->opening_time,
                'closing_time' => $request->closing_time,
                'user_id' => auth()->id(),
                'category_id' => $request->category_id
            ];

            $dataUploadFeatureImage = $this->storgeImageUpload($request, 'feature_image_path', 'destination');
            // dd($dataUploadFeatureImage);
            if (!empty($dataUploadFeatureImage)) {
                $dataDestinationUpdate['feature_image_name'] = $dataUploadFeatureImage['file_name'];
                $dataDestinationUpdate['feature_image_path'] = $dataUploadFeatureImage['file_path'];
            }
            // dd($dataDestinationUpdate);
            $desId = $request->input('destinationId');
            $this->destination->find($desId)->update($dataDestinationUpdate);
            $destinations = $this->destination->find($desId);
            // dd($destinations);
            // Ảnh chi tiết
            if ($request->hasFile('image_path')) {
                $this->destinationImage->where('destination_id', $desId)->delete();
                foreach ($request->image_path as $fileItems) {
                    $imageDetail = $this->storgeMultiImageUpload($fileItems, 'destination');
                    $destinations->images()->create([
                        'image_path' => $imageDetail['file_path'],
                        'image_name' => $imageDetail['file_name']
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('destination.index')->with('ok', 'Sửa địa điểm thành công!');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: ' . $exception->getMessage() . 'Line : ' . $exception->getLine());
        }
    }
    public function destroy($id)
    {
        return $this->deleteModalTrait($id, $this->destination);
    }
}
