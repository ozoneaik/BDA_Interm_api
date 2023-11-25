<?php
namespace App\Services;

use App\Enums\UserPackageStatus;
use App\Models\LogansTreePicture;
use App\Models\Longans;
use App\Models\User;
use App\Models\UserPackage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class LonganService {
    protected FileService $fileService;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function getLonganList($input)
    {
        $farm_id = Arr::get($input, 'farm_id');
        $search = Arr::get($input, 'search');

        return Longans::query()
            ->when($farm_id, function (Builder $q) use ($farm_id){
                $q->where('farm_id', $farm_id);
            })
            ->when($search, function (Builder $q) use ($search){
                $q->where('name', 'ilike', '%'.$search.'%');
            })
            ->orderByDesc('updated_at')
            ->get();
    }

    public function getLonganDetail(int $id)
    {
        return Longans::find($id);
    }

    /**
     * @param int $farm_id
     */
    public function getListLonganByFarm(int $farm_id): Collection|array
    {
        return Longans::query()
            ->with('farms')
            ->where('farm_id', $farm_id)
            ->get();
    }

    /**
     * @param int $longan_tree_id
     * @return mixed
     * @throws \Exception
     */
    public function createOrUpdateLonganTreeInFarm(int $longan_tree_id, $input): mixed
    {
        $longan_tree = Longans::find($longan_tree_id);
        $user_id = Arr::get($input, 'farmer_id');
        $user = User::find($user_id);
        $array_farm_id = $user->farms;
        if($array_farm_id && count($array_farm_id) > 0) {
            $array_farm_id = $array_farm_id->pluck('id')->toArray();

            $count_of_tree = Longans::query()
                ->whereIn('farm_id', $array_farm_id)
                ->count();

            $package_tree = 0;
            $packages = UserPackage::query()
                ->with('package')
                ->where('user_id', $user->id)
                ->where('status', UserPackageStatus::APPROVED->value)
                ->get();

            foreach ($packages as $package) {
                if($package->package) {
                    $package_tree += $package->package['amount_of_longan'];
                }
            }

            if($count_of_tree >= $package_tree && !$longan_tree_id) {
                return false;
            }
        }

        if(!$longan_tree){
            $longan_tree = new Longans();
        }

        $data = [
            'name' => Arr::get($input, 'name') ?? $longan_tree->name,
            'type' => Arr::get($input, 'type') ?? $longan_tree->type,
//            'trimming_at' => Arr::get($input, 'trimming_at') ?? $longan_tree->trimming_at ?? null,
            'farm_id' => Arr::get($input, 'farm_id') ?? $longan_tree->farm_id,
            'location' => Arr::get($input, 'location') ?? $longan_tree->location,
            'specie' => Arr::get($input, 'specie') ?? $longan_tree->specie,
        ];

        $longan_tree->fill($data);
        $longan_tree->save();

        $pictures = $input->file('pictures');
        $pictures_key = $input->get('pictures');
        $removesId = $input->get('removesId');

        if($removesId && count($removesId) > 0) {
            foreach ($removesId as $pic_id) {
                $longan_tree_pic = LogansTreePicture::find($pic_id);
                if($longan_tree_pic) {
                    $this->fileService->deleteFile($longan_tree_pic->picture_path);
                    $longan_tree_pic->delete();
                }
            }
        }

        if($pictures && count($pictures) > 0){
            foreach ($pictures as $index => $file) {
                $direction = $pictures_key[$index]['directions'];
                $seq = $pictures_key[$index]['seq'];
                if($file && gettype($file['file']) === 'object') {
                    [$file_path, $file_name] = $this->fileService->storeFile($file['file'], 'longan');

                    $longan_tree->pictures()->create([
                        'picture_path' => $file_path,
                        'directions' => $direction,
                        'seq' => $seq,
                        'longans_id' => $longan_tree->id
                    ]);
                }
            }
        }

        return $longan_tree;
    }
}
