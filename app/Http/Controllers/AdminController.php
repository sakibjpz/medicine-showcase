<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Enquiry;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'title' => 'Admin Dashboard',
            'pageCount' => Page::count(),
            'enquiryCount' => Enquiry::count(),
            'enquiryPending' => Enquiry::where('responded', false)->count(),
            'messageCount' => ContactMessage::count(),
            'messagePending' => ContactMessage::where('responded', false)->count(),
            'recentEnquiries' => Enquiry::latest()->limit(5)->get(),
            'recentMessages' => ContactMessage::latest()->limit(5)->get(),
            'homePage' => Page::where('slug', 'home')->first(),
        ]);
    }

    public function enquiries()
    {
        return view('admin.enquiries', [
            'title' => 'Professional Enquiries',
            'enquiries' => Enquiry::latest()->paginate(15),
        ]);
    }

    public function showEnquiry(Enquiry $enquiry)
    {
        return view('admin.enquiry-detail', [
            'title' => 'Enquiry #' . $enquiry->id,
            'enquiry' => $enquiry,
        ]);
    }

    public function toggleEnquiry(Enquiry $enquiry)
    {
        $enquiry->update(['responded' => ! $enquiry->responded]);

        return back()->with('success', 'Enquiry status updated.');
    }

    public function destroyEnquiry(Enquiry $enquiry)
    {
        $enquiry->delete();

        return back()->with('success', 'Enquiry deleted.');
    }

    public function messages()
    {
        return view('admin.messages', [
            'title' => 'Contact Messages',
            'messages' => ContactMessage::latest()->paginate(15),
        ]);
    }

    public function showMessage(ContactMessage $message)
    {
        return view('admin.message-detail', [
            'title' => 'Message #' . $message->id,
            'message' => $message,
        ]);
    }

    public function toggleMessage(ContactMessage $message)
    {
        $message->update(['responded' => ! $message->responded]);

        return back()->with('success', 'Message status updated.');
    }

    public function destroyMessage(ContactMessage $message)
    {
        $message->delete();

        return back()->with('success', 'Message deleted.');
    }

    // Page management

    public function pages()
    {
        return view('admin.pages', [
            'title' => 'Pages',
            'pages' => Page::orderBy('heading')->paginate(15),
        ]);
    }

    public function createPage()
    {
        return view('admin.page-form', [
            'title' => 'Create Page',
            'page' => null,
        ]);
    }

    public function storePage(Request $request)
    {
        $validated = $request->validate([
            'heading' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'lead' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'breadcrumbs' => 'nullable|string',
            'is_published' => 'boolean',
            'banner_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'banner_image_url' => 'nullable|url|max:255',
            'remove_banner_image' => 'nullable|boolean',
        ]);

        $validated['breadcrumbs'] = $this->parseBreadcrumbs($validated['breadcrumbs'] ?? '');
        $validated['slug'] = $this->uniqueSlug($this->slugFromHeading($validated['heading']));
        $validated['banner_image'] = $this->pageBannerImage($request, null);

        Page::create($validated);

        return redirect()->route('admin.pages')->with('success', 'Page created.');
    }

    public function editPage(Page $page)
    {
        return view('admin.page-form', [
            'title' => 'Edit Page: ' . $page->heading,
            'page' => $page,
        ]);
    }

    public function updatePage(Request $request, Page $page)
    {
        $validated = $request->validate([
            'heading' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'lead' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'breadcrumbs' => 'nullable|string',
            'is_published' => 'boolean',
            'banner_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'banner_image_url' => 'nullable|url|max:255',
            'remove_banner_image' => 'nullable|boolean',
        ]);

        $validated['breadcrumbs'] = $this->parseBreadcrumbs($validated['breadcrumbs'] ?? '');
        $validated['banner_image'] = $this->pageBannerImage($request, $page);

        $page->update($validated);

        return redirect()->route('admin.pages')->with('success', 'Page updated.');
    }

    public function destroyPage(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages')->with('success', 'Page deleted.');
    }

    private function parseBreadcrumbs(string $input): ?array
    {
        $lines = array_filter(array_map('trim', explode("\n", $input)));
        $breadcrumbs = [];

        foreach ($lines as $line) {
            $breadcrumbs[] = ['label' => $line];
        }

        return $breadcrumbs ?: null;
    }

    private function slugFromHeading(string $heading): string
    {
        $slug = Str::slug($heading);

        return $slug ?: 'page';
    }

    private function uniqueSlug(string $base): string
    {
        $slug = $base;
        $counter = 1;

        while (Page::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter++;
            if ($counter > 1000) {
                $slug = $base . '-' . time();
                break;
            }
        }

        return $slug;
    }

    private function pageBannerImage(Request $request, ?Page $page): ?string
    {
        if ($request->boolean('remove_banner_image')) {
            return null;
        }

        if ($request->hasFile('banner_image_file')) {
            $path = $request->file('banner_image_file')->store('pages/banners', 'public');
            return asset('storage/' . $path);
        }

        $url = $request->input('banner_image_url');
        if (! empty($url)) {
            return $url;
        }

        return $page?->banner_image;
    }
}
