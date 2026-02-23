use App\Models\Student;
use Illuminate\Http\Request;

class MuhdirController extends Controller
{
    public function dashboard()
    {
        $students = auth()->user()->students; // Assuming we add this relation
        return view('muhdir.dashboard', compact('students'));
    }
}
