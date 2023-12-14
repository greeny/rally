<?php declare(strict_types=1);

namespace Rally\Modules\PublicModule;

use Rally\Model\Entity\Member;
use Rally\Model\Repository\MemberRepository;
use Rally\UI\Forms\MemberForm;
use Rally\UI\Forms\MemberFormFactory;

final class MemberPresenter extends BaseSecuredPresenter
{

	private Member $member;

	public function __construct(
		private readonly MemberRepository $memberRepository,
		private readonly MemberFormFactory $memberFormFactory,
	)
	{
		parent::__construct();
	}

	public function renderDefault(): void
	{
		$this->template->add('members', $this->memberRepository->getAll());
	}

	public function actionEdit(int $id): void
	{
		$member = $this->memberRepository->getById($id);
		if (!$member) {
			$this->error('Member not found');
		}

		$this->member = $member;
	}

	protected function createComponentCreateMemberForm(): MemberForm
	{
		$form = $this->memberFormFactory->create();
		$form->onSuccess[] = function () {
			$this->flashSuccess('member.created');
			$this->redirect('Member:default');
		};
		return $form;
	}

	protected function createComponentEditMemberForm(): MemberForm
	{
		$form = $this->memberFormFactory->create($this->member);
		$form->onSuccess[] = function () {
			$this->flashSuccess('member.edited');
			$this->redirect('Member:default');
		};
		return $form;
	}

}
