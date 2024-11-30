<?php

namespace App\Http\Controllers;

use App\Models\SongModel;
use App\Models\StatisticModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SongController extends Controller
{
    public function renderListOfSongs()
    {
        $songs = DB::table('bai_hat')
            ->join('album as song_album', 'bai_hat.ma_album', '=', 'song_album.ma_album')
            ->join('tai_khoan', 'bai_hat.ma_tk_artist', '=', 'tai_khoan.ma_tk')
            ->join('user', 'tai_khoan.ma_tk', '=', 'user.ma_tk')
            ->join('theloai_baihat', 'bai_hat.ma_bai_hat', '=', 'theloai_baihat.ma_bai_hat')
            ->join('the_loai', 'the_loai.ma_the_loai', '=', 'theloai_baihat.ma_the_loai')
            ->join('chat_luong_bai_hat', 'bai_hat.ma_bai_hat', '=', 'chat_luong_bai_hat.ma_bai_hat')
            ->select(
                'bai_hat.*',
                'bai_hat.hinh_anh as bai_hat_hinh_anh', // Alias cho hinh_anh của bảng bai_hat
                'song_album.ten_album as album_name',
                'user.ten_user as artist_name',
                'chat_luong_bai_hat.*',
                'the_loai.*',
            )
            ->get();

        if ($songs->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'ERROR 404 - No songs found'
            ], Response::HTTP_NOT_FOUND);
        }

        $formattedSongs = $songs->map(function ($item) {
            return [
                'ma_bai_hat' => $item->ma_bai_hat,
                'ten_bai_hat' => $item->ten_bai_hat,
                'album' => $item->album_name, // Alias từ bảng album
                'artist' => $item->artist_name, // Alias từ bảng user
                'thoi_luong' => $item->thoi_luong,
                'luot_nghe' => $item->luot_nghe,
                'hinh_anh' => $item->bai_hat_hinh_anh, // Sử dụng alias của hinh_anh từ bảng bai_hat
                'ngay_phat_hanh' => $item->ngay_phat_hanh,
                'chat_luong' => $item->chat_luong,
                'link_bai_hat' => $item->link_bai_hat,
                'ma_the_loai' => $item->ma_the_loai,
                'ten_the_loai' => $item->ten_the_loai,
                'trang_thai' => $item->trang_thai
            ];
        });

        return response()->json([
            'data' => $formattedSongs,
            'message' => 'Get all songs successfully',
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }


    public function renderListOfLikesForSong()
    {
        $songs = DB::table('bai_hat')
            ->join('album as song_album', 'bai_hat.ma_album', '=', 'song_album.ma_album')
            ->join('tai_khoan', 'bai_hat.ma_tk_artist', '=', 'tai_khoan.ma_tk')
            ->join('user', 'tai_khoan.ma_tk', '=', 'user.ma_tk')
            ->join('theloai_baihat', 'bai_hat.ma_bai_hat', '=', 'theloai_baihat.ma_bai_hat')
            ->join('the_loai', 'the_loai.ma_the_loai', '=', 'theloai_baihat.ma_the_loai')
            ->join('chat_luong_bai_hat', 'bai_hat.ma_bai_hat', '=', 'chat_luong_bai_hat.ma_bai_hat')
            ->join('luot_thich_bai_hat', 'bai_hat.ma_bai_hat', '=', 'luot_thich_bai_hat.ma_bai_hat')
            ->select(
                'bai_hat.*',
                'bai_hat.hinh_anh as bai_hat_hinh_anh', // Alias cho hinh_anh của bảng bai_hat
                'song_album.ten_album as album_name',
                'user.ten_user as artist_name',
                'chat_luong_bai_hat.*',
                'the_loai.*',
                DB::raw('COUNT(luot_thich_bai_hat.ma_bai_hat) as so_luot_thich')
            )
            ->groupBy(
                'bai_hat.ma_bai_hat',
                'bai_hat.ten_bai_hat',
                'bai_hat.thoi_luong',
                'bai_hat.trang_thai',
                'bai_hat.luot_nghe',
                'bai_hat.hinh_anh',
                'bai_hat.ngay_phat_hanh',
                'bai_hat.ma_album',
                'bai_hat.ma_tk_artist',
                'bai_hat.doanh_thu',
                'song_album.ten_album',
                'user.ten_user',
                'chat_luong_bai_hat.chat_luong',
                'chat_luong_bai_hat.link_bai_hat',
                'the_loai.ma_the_loai',
                'the_loai.ten_the_loai'
            )
            ->get();

        if ($songs->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'ERROR 404 - No songs found'
            ], Response::HTTP_NOT_FOUND);
        }

        $formattedSongs = $songs->map(function ($item) {
            return [
                'ma_bai_hat' => $item->ma_bai_hat,
                'ten_bai_hat' => $item->ten_bai_hat,
                'album' => $item->album_name, // Alias từ bảng album
                'artist' => $item->artist_name, // Alias từ bảng user
                'thoi_luong' => $item->thoi_luong,
                'luot_nghe' => $item->luot_nghe,
                'hinh_anh' => $item->bai_hat_hinh_anh, // Sử dụng alias của hinh_anh từ bảng bai_hat
                'ngay_phat_hanh' => $item->ngay_phat_hanh,
                'chat_luong' => $item->chat_luong,
                'link_bai_hat' => $item->link_bai_hat,
                'ma_the_loai' => $item->ma_the_loai,
                'ten_the_loai' => $item->ten_the_loai,
                'luot_thich' => $item->so_luot_thich,
                'trang_thai' => $item->trang_thai
            ];
        });

        return response()->json([
            'data' => $formattedSongs,
            'message' => 'Get all songs successfully',
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    public function renderListOfArtists()
    {
        $artists = DB::table('tai_khoan')
            ->join('user', 'user.ma_tk', '=', 'tai_khoan.ma_tk')
            ->join('phan_quyen', 'tai_khoan.ma_phan_quyen', '=', 'phan_quyen.ma_phan_quyen')
            ->select('tai_khoan.*', 'user.*')
            ->where('tai_khoan.ma_phan_quyen', 'AUTH0002')
            ->get();

        if ($artists->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'ERROR 404'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $artists->map(function ($item) {
                return [
                    'ma_artist' => $item->ma_tk,
                    'ten_artist' => $item->ten_user,
                    'anh_dai_dien' => $item->anh_dai_dien
                ];
            }),
            'message' => 'Get all artists successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    public function renderListOfSongsByArtist()
    {
        // Lấy danh sách nghệ sĩ và bài hát của họ
        $artists = DB::table('tai_khoan')
            ->join('user', 'user.ma_tk', '=', 'tai_khoan.ma_tk')
            ->join('phan_quyen', 'tai_khoan.ma_phan_quyen', '=', 'phan_quyen.ma_phan_quyen')
            ->join('bai_hat', 'bai_hat.ma_tk_artist', '=', 'tai_khoan.ma_tk')
            ->join('album', 'album.ma_album', '=', 'bai_hat.ma_album')
            ->select(
                'tai_khoan.*',
                'user.*',
                'bai_hat.*',
                'bai_hat.hinh_anh as song_image',
                'album.*',
                'album.hinh_anh as album_image'
            )
            ->where('tai_khoan.ma_phan_quyen', 'AUTH0002') // Chỉ lấy tài khoản có quyền nghệ sĩ
            ->get()
            ->groupBy('ma_tk'); // Nhóm dữ liệu theo mã tài khoản (nghệ sĩ)

        // Kiểm tra nếu không tìm thấy nghệ sĩ nào
        if ($artists->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Không tìm thấy nghệ sĩ và bài hát nào',
            ], Response::HTTP_NOT_FOUND);
        }

        // Chuẩn bị dữ liệu trả về
        $data = $artists->map(function ($songs, $ma_artist) {
            $artistInfo = $songs->first(); // Lấy thông tin cơ bản của nghệ sĩ
            return [
                'ma_artist' => $ma_artist,
                'ten_artist' => $artistInfo->ten_user,
                'bai_hat' => $songs->map(function ($song) {
                    return [
                        'ma_bai_hat' => $song->ma_bai_hat,
                        'ten_bai_hat' => $song->ten_bai_hat,
                        'thoi_luong' => $song->thoi_luong,
                        'ngay_phat_hanh' => $song->ngay_phat_hanh,
                        'trang_thai' => $song->trang_thai,
                        'doanh_thu' => $song->doanh_thu,
                        'luot_nghe' => $song->luot_nghe,
                        'hinh_anh' => $song->song_image,
                        'album' => $song->ten_album
                    ];
                })->values(),
            ];
        })->values();

        // Trả về kết quả
        return response()->json([
            'data' => $data,
            'message' => 'Lấy danh sách nghệ sĩ và bài hát thành công',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }


    public function renderListOfSongsBySearchedArtist($ma_artist)
    {
        // Lấy thông tin nghệ sĩ và bài hát của họ
        $artists = DB::table('tai_khoan')
            ->join('user', 'user.ma_tk', '=', 'tai_khoan.ma_tk')
            ->join('phan_quyen', 'tai_khoan.ma_phan_quyen', '=', 'phan_quyen.ma_phan_quyen')
            ->leftJoin('bai_hat', 'bai_hat.ma_tk_artist', '=', 'tai_khoan.ma_tk') // Sử dụng leftJoin để vẫn lấy được nghệ sĩ không có bài hát
            ->leftJoin('album', 'album.ma_album', '=', 'bai_hat.ma_album')
            ->select(
                'tai_khoan.*',
                'user.*',
                'bai_hat.*',
                'bai_hat.hinh_anh as song_image',
                'album.ten_album',
                'album.ma_album',
                'album.hinh_anh as album_image'
            )
            ->where('tai_khoan.ma_phan_quyen', 'AUTH0002') // Chỉ lấy tài khoản có quyền nghệ sĩ
            ->where('tai_khoan.ma_tk', '=', $ma_artist)
            ->get()
            ->groupBy('ma_tk'); // Nhóm dữ liệu theo mã tài khoản (nghệ sĩ)

        // Kiểm tra nếu không tìm thấy nghệ sĩ nào
        if ($artists->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Không tìm thấy nghệ sĩ',
            ], Response::HTTP_NOT_FOUND);
        }

        // Chuẩn bị dữ liệu trả về
        $data = $artists->map(function ($songs, $ma_artist) {
            $artistInfo = $songs->first(); // Lấy thông tin cơ bản của nghệ sĩ
            $songData = $songs->filter(function ($song) {
                return !is_null($song->ma_bai_hat); // Lọc những bài hát không null
            })->map(function ($song) {
                return [
                    'ma_bai_hat' => $song->ma_bai_hat,
                    'ten_bai_hat' => $song->ten_bai_hat,
                    'thoi_luong' => $song->thoi_luong,
                    'ngay_phat_hanh' => $song->ngay_phat_hanh,
                    'trang_thai' => $song->trang_thai,
                    'doanh_thu' => $song->doanh_thu,
                    'luot_nghe' => $song->luot_nghe,
                    'hinh_anh' => $song->song_image,
                    'ma_album' => $song->ma_album,
                    'ten_album' => $song->ten_album,
                ];
            })->values();

            return [
                'ma_artist' => $ma_artist,
                'ten_artist' => $artistInfo->ten_user,
                'anh_dai_dien' => $artistInfo->anh_dai_dien,
                'bai_hat' => $songData->isEmpty() ? null : $songData, // Nếu không có bài hát thì trả null
            ];
        })->values();

        // Trả về kết quả
        return response()->json([
            'message' => 'Lấy danh sách nghệ sĩ và bài hát thành công',
            'status' => Response::HTTP_OK,
            'data' => $data,
        ], Response::HTTP_OK);
    }



    public function renderListOfSongsWithCollabArtist($ma_bai_hat)
    {
        $songWithCollabArtists = DB::table('bai_hat_subartist')
            ->join('bai_hat', 'bai_hat.ma_bai_hat', '=', 'bai_hat_subartist.ma_bai_hat')
            ->join('tai_khoan as artist_tai_khoan', 'artist_tai_khoan.ma_tk', '=', 'bai_hat.ma_tk_artist')
            ->join('tai_khoan as subartist_tai_khoan', 'subartist_tai_khoan.ma_tk', '=', 'bai_hat_subartist.ma_subartist')
            ->join('user as user_subartist', 'user_subartist.ma_tk', '=', 'subartist_tai_khoan.ma_tk')
            ->join('user as user_artist', 'user_artist.ma_tk', '=', 'artist_tai_khoan.ma_tk')
            ->select(
                'bai_hat.ma_bai_hat',
                'bai_hat.ten_bai_hat',
                'user_artist.ma_tk as ma_artist',
                'user_artist.ten_user as ten_artist',
                'user_artist.anh_dai_dien as anh_dai_dien_artist',
                'user_subartist.ma_tk as ma_collab_artist',
                'user_subartist.ten_user as ten_collab_artist',
                'user_subartist.anh_dai_dien as anh_dai_dien_collab_artist'
            )
            ->where('bai_hat.ma_bai_hat', $ma_bai_hat)
            ->get();

        if ($songWithCollabArtists->isEmpty()) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No collaborators found for this song',
            ], Response::HTTP_NOT_FOUND);
        }

        // Nhóm dữ liệu theo bài hát
        $result = $songWithCollabArtists->groupBy('ma_bai_hat')->map(function ($groupedSongs) {
            $first = $groupedSongs->first();
            return [
                'ma_bai_hat' => $first->ma_bai_hat,
                'ten_bai_hat' => $first->ten_bai_hat,
                'ma_artist' => $first->ma_artist,
                'ten_artist' => $first->ten_artist,
                'anh_dai_dien_artist' => $first->anh_dai_dien_artist,
                'collab_artists' => $groupedSongs->map(function ($artist) {
                    return [
                        'ma_collab_artist' => $artist->ma_collab_artist,
                        'ten_collab_artist' => $artist->ten_collab_artist,
                        'anh_dai_dien_collab_artist' => $artist->anh_dai_dien_collab_artist,
                    ];
                }),
            ];
        })->values();

        return response()->json([
            'data' => $result,
            'message' => 'Get song and its collab artists successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    // Hiển thị thông tin chi tiết của một bài hát
    public function renderSongDetails($ma_bai_hat)
    {
        // Tìm bài hát dựa vào mã bài hát
        $song = DB::table('bai_hat')
            ->leftJoin('album', 'bai_hat.ma_album', '=', 'album.ma_album')
            ->leftJoin('tai_khoan as tk_artist', 'bai_hat.ma_tk_artist', '=', 'tk_artist.ma_tk')
            ->leftJoin('user as user_artist', 'tk_artist.ma_tk', '=', 'user_artist.ma_tk')
            ->select(
                'bai_hat.*',
                'album.ten_album',
                'tk_artist.*',
                'user_artist.ten_user as ten_artist'
            )
            ->where('bai_hat.ma_bai_hat', $ma_bai_hat)
            ->first();

        if (!$song) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Song not found',
            ], Response::HTTP_NOT_FOUND);
        }

        // Lấy danh sách subartist
        $subartists = DB::table('bai_hat_subartist')
            ->join('tai_khoan as tk_subartist', 'bai_hat_subartist.ma_subartist', '=', 'tk_subartist.ma_tk')
            ->join('user as user_subartist', 'tk_subartist.ma_tk', '=', 'user_subartist.ma_tk')
            ->where('bai_hat_subartist.ma_bai_hat', $ma_bai_hat)
            ->select(
                'user_subartist.ma_tk as ma_subartist',
                'user_subartist.ten_user as ten_subartist',
                'user_subartist.anh_dai_dien as anh_dai_dien_subartist'
            )
            ->get();

        // Lấy danh sách thể loại
        $genres = DB::table('theloai_baihat')
            ->join('the_loai', 'theloai_baihat.ma_the_loai', '=', 'the_loai.ma_the_loai')
            ->where('theloai_baihat.ma_bai_hat', $ma_bai_hat)
            ->select(
                'the_loai.ma_the_loai',
                'the_loai.ten_the_loai'
            )
            ->get();

        $links = DB::table('chat_luong_bai_hat')
            ->join('bai_hat', 'chat_luong_bai_hat.ma_bai_hat', '=', 'bai_hat.ma_bai_hat')
            ->where('chat_luong_bai_hat.ma_bai_hat', $ma_bai_hat)
            ->select(
                'chat_luong_bai_hat.chat_luong',
                'chat_luong_bai_hat.link_bai_hat',
            )
            ->get();

        // Trả về dữ liệu JSON
        return response()->json([
            'data' => [
                'ma_bai_hat' => $song->ma_bai_hat,
                'ten_bai_hat' => $song->ten_bai_hat,
                'album' => $song->ten_album,
                'ma_artist' => $song->ma_tk,
                'artist' => $song->ten_artist,
                'thoi_luong' => $song->thoi_luong,
                'trang_thai' => $song->trang_thai,
                'luot_nghe' => $song->luot_nghe,
                'hinh_anh' => $song->hinh_anh,
                'ngay_phat_hanh' => $song->ngay_phat_hanh,
                'link_bai_hat' => $links->map(function ($link) {
                    return [
                        'chat_luong' => $link->chat_luong,
                        'link_bai_hat' => $link->link_bai_hat,
                    ];
                }),
                'subartists' => $subartists->map(function ($subartist) {
                    return [
                        'ma_subartist' => $subartist->ma_subartist,
                        'ten_subartist' => $subartist->ten_subartist,
                        'anh_dai_dien_subartist' => $subartist->anh_dai_dien_subartist,
                    ];
                }),
                'the_loai' => $genres->map(function ($genre) {
                    return [
                        'ma_the_loai' => $genre->ma_the_loai,
                        'ten_the_loai' => $genre->ten_the_loai,
                    ];
                }),
            ],
            'message' => 'Song details retrieved successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    public function renderListOfSongsLikedByAccount($ma_tai_khoan)
    {
        // Lấy danh sách bài hát được thích bởi tài khoản
        $likedSongs = DB::table('luot_thich_bai_hat')
            ->join('bai_hat', 'luot_thich_bai_hat.ma_bai_hat', '=', 'bai_hat.ma_bai_hat')
            ->leftJoin('album', 'bai_hat.ma_album', '=', 'album.ma_album')
            ->leftJoin('tai_khoan as tk_artist', 'bai_hat.ma_tk_artist', '=', 'tk_artist.ma_tk')
            ->select(
                'bai_hat.ma_bai_hat',
                'bai_hat.ten_bai_hat',
                'bai_hat.thoi_luong',
                'bai_hat.luot_nghe',
                'bai_hat.hinh_anh',
                'bai_hat.ngay_phat_hanh',
                'bai_hat.trang_thai',
                'album.ten_album',
            )
            ->where('luot_thich_bai_hat.ma_tk', $ma_tai_khoan)
            ->get();

        // Kiểm tra nếu không có bài hát nào được tìm thấy
        if ($likedSongs->isEmpty()) {
            return response()->json([
                'data' => [],
                'message' => 'No liked songs found for the account',
                'status' => Response::HTTP_NOT_FOUND,
            ], Response::HTTP_NOT_FOUND);
        }

        // Định dạng dữ liệu trả về
        $data = $likedSongs->map(function ($song) {
            return [
                'ma_bai_hat' => $song->ma_bai_hat,
                'ten_bai_hat' => $song->ten_bai_hat,
                'thoi_luong' => $song->thoi_luong,
                'luot_nghe' => $song->luot_nghe,
                'hinh_anh' => $song->hinh_anh,
                'ngay_phat_hanh' => $song->ngay_phat_hanh,
                'trang_thai' => $song->trang_thai,
                'album' => $song->ten_album,
            ];
        });

        // Trả về danh sách bài hát dưới dạng JSON
        return response()->json([
            'data' => $data,
            'message' => 'List of liked songs retrieved successfully',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    // Lưu trữ một bài hát mới
    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'ten_bai_hat' => 'required|string|max:255',
            'ma_tk_artist' => 'required|exists:tai_khoan,ma_tk', // Kiểm tra tài khoản nghệ sĩ tồn tại
            'ma_album' => 'nullable|exists:album,ma_album', // Album có thể null
            'thoi_luong' => 'required|integer|min:1', // Thời lượng phải là số nguyên dương
            'trang_thai' => 'required|in:0,1', // Chỉ chấp nhận giá trị 0 hoặc 1
            'hinh_anh' => 'nullable|string', // Hình ảnh có thể là đường dẫn
            'ngay_phat_hanh' => 'required|date', // Ngày phát hành hợp lệ
            'doanh_thu' => 'nullable|numeric|min:0', // Doanh thu tối thiểu là 0
            'the_loai' => 'required|array|min:1', // Danh sách thể loại, ít nhất 1 thể loại
            'the_loai.*' => 'exists:the_loai,ma_the_loai', // Kiểm tra thể loại tồn tại
            'links' => 'required|array|size:2', // Bài hát cần 2 link cho 2 chất lượng
            'links.cao' => 'required|string', // Link chất lượng cao
            'links.thap' => 'required|string', // Link chất lượng thấp
            'subartists' => 'nullable|array', // Danh sách subartists có thể null
            'subartists.*' => 'exists:tai_khoan,ma_tk', // Mã tài khoản subartist phải tồn tại
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Tạo mã bài hát tự động dạng BHxxxx
        $lastSong = DB::table('bai_hat')->orderBy('ma_bai_hat', 'desc')->first();
        $nextId = $lastSong ? (int)substr($lastSong->ma_bai_hat, 2) + 1 : 1;
        $ma_bai_hat = 'BH' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        // Tạo bài hát mới
        $song = [
            'ma_bai_hat' => $ma_bai_hat,
            'ten_bai_hat' => $request->ten_bai_hat,
            'ma_tk_artist' => $request->ma_tk_artist,
            'ma_album' => $request->ma_album,
            'thoi_luong' => $request->thoi_luong,
            'trang_thai' => $request->trang_thai,
            'hinh_anh' => $request->hinh_anh,
            'ngay_phat_hanh' => $request->ngay_phat_hanh,
            'doanh_thu' => $request->doanh_thu ?? 0,
            'luot_nghe' => 0, // Mặc định lượt nghe là 0
        ];

        DB::table('bai_hat')->insert($song);

        // Lưu các thể loại vào bảng theloai_baihat
        foreach ($request->the_loai as $ma_the_loai) {
            DB::table('theloai_baihat')->insert([
                'ma_bai_hat' => $ma_bai_hat,
                'ma_the_loai' => $ma_the_loai,
            ]);
        }

        // Lưu link nhạc vào bảng chat_luong_bai_hat
        DB::table('chat_luong_bai_hat')->insert([
            ['ma_bai_hat' => $ma_bai_hat, 'chat_luong' => 'cao', 'link_bai_hat' => $request->links['cao']],
            ['ma_bai_hat' => $ma_bai_hat, 'chat_luong' => 'thap', 'link_bai_hat' => $request->links['thap']],
        ]);

        // Lưu subartists vào bảng bai_hat_subartist (nếu có)
        if ($request->filled('subartists')) {
            foreach ($request->subartists as $subartist) {
                DB::table('bai_hat_subartist')->insert([
                    'ma_bai_hat' => $ma_bai_hat,
                    'ma_subartist' => $subartist,
                ]);
            }
        }

        // Trả về phản hồi
        return response()->json([
            'message' => 'Bài hát đã được tạo thành công!',
            'data' => $song,
        ], 201);
    }

    // Cập nhật thông tin bài hát
    public function update(Request $request, $ma_bai_hat)
    {
        $song = SongModel::find($ma_bai_hat);
        if (!$song) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Song not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'ten_bai_hat' => 'required',
            'thoi_luong' => 'required|numeric',
            'hinh_anh' => 'required',
            'ma_album' => 'required',
            'link_bai_hat' => 'required',
            'ngay_phat_hanh' => 'required|date',
            'trang_thai' => 'required|integer|in:0,1',
            'luot_nghe' => 'required|integer',
            'doanh_thu' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $song->update([
                'ten_bai_hat' => $request->ten_bai_hat,
                'thoi_luong' => $request->thoi_luong,
                'hinh_anh' => $request->hinh_anh,
                'ma_album' => $request->ma_album,
                'link_bai_hat' => $request->link_bai_hat,
                'ngay_phat_hanh' => $request->ngay_phat_hanh,
                'trang_thai' => $request->trang_thai,
                'luot_nghe' => $request->luot_nghe,
                'doanh_thu' => $request->doanh_thu,
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Song updated successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Song update failed: ' . $e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Song update failed',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateSongListens(Request $request, $ma_bai_hat)
    {
        try {
            // Tìm bài hát theo mã bài hát
            $song = SongModel::find($ma_bai_hat);
            if (!$song) {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Song not found',
                ], Response::HTTP_NOT_FOUND);
            }

            // Tăng lượt thích của bài hát
            $song->luot_nghe += 1;
            $song->save();

            // Cập nhật lượt thích trong bảng `thong_ke`
            $ngay_thong_ke = now()->format('Y-m-d'); // Ngày hiện tại
            $statistic = StatisticModel::firstOrCreate(
                ['ngay_thong_ke' => $ngay_thong_ke, 'ma_bai_hat' => $ma_bai_hat],
                ['doanh_thu' => 0, 'luot_nghe' => 0]
            );

            // Tăng lượt thích trong bảng `thong_ke`
            $statistic->luot_nghe += 1;
            $statistic->save();

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Song likes incremented successfully',
                'data' => [
                    'ma_bai_hat' => $song->ma_bai_hat,
                    'luot_nghe' => $song->luot_nghe,
                    'thong_ke' => [
                        'ngay_thong_ke' => $statistic->ngay_thong_ke,
                        'luot_nghe' => $statistic->luot_nghe,
                    ],
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Ghi log lỗi chi tiết nếu xảy ra vấn đề
            Log::error('Error updating song likes: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Incrementing song likes failed',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Cập nhật trạng thái bài hát về đã xóa
    public function destroy($ma_bai_hat)
    {
        // Tìm bài hát dựa trên mã bài hát
        $song = SongModel::find($ma_bai_hat);

        if (!$song) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Song not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            // Chuyển trạng thái bài hát thành 0 (soft delete)
            $song->trang_thai = 0;
            $song->save();

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Song deleted (soft delete) successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Ghi log lỗi nếu xảy ra vấn đề
            Log::error('Song deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Song deletion failed',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
