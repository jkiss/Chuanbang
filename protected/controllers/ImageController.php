<?php
Yii::import('application.components.utils.qqFileUploader');
class ImageController extends Controller
{
    private $pc_image_server;
    private $pc_upload_dir;

    private $allowedImageExtensions = array('jpg','jpeg','png',);
    // 10M
    private $sizeLimit = 10285760;

    public function init() {
        parent::init();
        $this->pc_image_server = 'http://'.Yii::app()->params['pc_image_server'];
        $this->pc_upload_dir = Yii::app()->params['pc_upload_dir'];
    }

    // 图片上传
	public function actionUpload()
	{
        // 提问
        if(isset($_POST['tag']) && !empty($_POST['tag'])) {
            if('q' == $_POST['tag'] && Yii::app()->user->isGuest)
                exit(htmlspecialchars(json_encode(array('success'=>false)), ENT_NOQUOTES));
        }

		$uploader = new qqFileUploader($this->allowedImageExtensions, $this->sizeLimit);

        $relative_dir = AppUtils::hash_dir();
        $abs_dir = $this->pc_upload_dir.'/'.$relative_dir;
		$result = $uploader->handleUpload($abs_dir);

		if(is_array($result) && array_key_exists('success', $result) && $result['success'] === true) {
            $result['url'] = $this->pc_image_server.'/'.$relative_dir.'/'.$uploader->getUploadName();

            if(isset($_POST['tag']) && !empty($_POST['tag'])) {
                // 提问
                if('q' == $_POST['tag']) {
                    $user_id = Yii::app()->user->id;
                    $success = QuestionService::addDraft($user_id, $result['url']);
                    if(!$success) $result['success'] = false;
                }
            }
            // 删除上传图片
            if(!$result['success']) {
                AppUtils::deleteImage($result['url']);
                unset($result['url']);
            }
		}
		// to pass data through iframe you will need to encode all html tags
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}
}