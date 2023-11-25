<?php
namespace App\Services;
use App\Models\Farms;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rules\In;

class FarmService {

    protected FileService $fileService;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * @param array $columns
     * @param array $relation
     * @return Collection|array
     */
    public function getListFarms($input, array $columns=[], array $relation=[]): Collection|array
    {
        $user_id = Arr::get($input, 'farmer_id') ?? auth()->id();
        return Farms::query()
            ->when($relation, function ($q) use ($relation) {
                $q->with($relation);
            })
            ->when($columns, function ($q) use ($columns) {
                $q->select($columns);
            })
            ->where('user_id', $user_id)
            ->get();
    }

    public function createOrUpdateFarms($input, $id=0)
    {
        $farm = Farms::find($id);

        if(!$farm) {
            $farm = new Farms();
        }

        $file = $input->file('file');

        if($file && gettype($file) === 'object'){
            [$file_path, $file_name] = $this->fileService->storeFile($file, 'farms');
        }

        $polygons = $farm->polygons ?? null;
        if(Arr::get($input, 'polygons')) {
            $polygons = [];
            $input_polygons = Arr::get($input, 'polygons');
            if(count($input_polygons) > 0) {
                foreach ($input_polygons as $input_polygon) {
                    $new_input_polygon = [
                        "lat" => floatval($input_polygon['lat']),
                        "lng" => floatval($input_polygon['lng']),
                    ];

                    array_push($polygons, $new_input_polygon);
                }
            }
        }

        $farm->fill([
            'name' => Arr::get($input, 'name') ?? $farm->name,
            'amount_of_rai' => Arr::get($input, 'amount_of_rai') ?? $farm->amount_of_rai,
            'amount_of_tree' => Arr::get($input, 'amount_of_tree') ?? $farm->amount_of_tree,
            'age_of_rai' => Arr::get($input, 'age_of_rai') ?? $farm->age_of_rai,
            'species' => Arr::get($input, 'species') ?? $farm->species,
            'location' => Arr::get($input, 'location') ?? $farm->location,
            'trimming_date' => Arr::get($input, 'trimming_date') ?? $farm->trimming_date,
            'user_id' => auth()->id(),
            'picture_path' => $file_path ?? $farm->picture_path,
            'amount_of_square_meters' => Arr::get($input, 'amount_of_square_meters') ?? $farm->amount_of_square_meters,
            'polygons' => $polygons
        ]);
        $farm->save();
        return $farm;
    }

    public function getListLonganByFarm($farm_id, $input)
    {
        $search = Arr::get($input, 'search');
        $type = Arr::get($input, 'type');

        return Farms::query()
            ->with('longans')
            ->where('id', $farm_id)
            ->when($search, function($q) use ($search){
                $q->whereHas('longans', function ($q) use ($search){
                    $q->where('name', 'ilike', '%'.$search.'%');
                });
            })
            ->when($type, function ($q) use ($type){
                $q->whereHas('longans', function ($q) use ($type){
                    $q->where('type', $type);
                });
              })
            ->first();
    }
}
